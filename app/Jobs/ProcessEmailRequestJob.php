<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessEmailRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestId;
    protected $token;
    protected $adminName;

    /**
     * Create a new job instance.
     */
    public function __construct($requestId, $token, $adminName)
    {
        $this->requestId = $requestId;
        $this->token = $token;
        $this->adminName = $adminName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $request = EmailRequest::find($this->requestId);
        
        if (!$request) {
            return; // Request not found, abort
        }

        $email = trim($request->email);
        $firstName = $request->firstname;
        $lastName = $request->lastname;
        $campusId = $request->campus_id;
        $password = $request->password;

        if (empty($email)) {
            $request->status = 'rejected';
            $request->save();
            return;
        }

        // Fetch CampusTerm to get the mapped tenant_id for correct Organizational Unit placement
        $campusTerm = \App\Models\CampusTerm::where('campus_id', $campusId)->first();
        $apiTenantId = $campusTerm ? ($campusTerm->tenant_id ?? $campusId) : $campusId;

        $orgUnit = match ((int) $apiTenantId) {
            1 => '/Main Campus/Students',
            3 => '/Kidapawan City Campus/Students',
            4 => '/Main Campus/Students/Graduate School',
            default => '/'
        };

        try {
            // Check if user exists in Google
            $googleCheck = Http::withToken($this->token)
                ->get("https://admin.googleapis.com/admin/directory/v1/users/{$email}");

            if ($googleCheck->successful()) {
                // If user exists, update password
                $googleUpdate = Http::withToken($this->token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->put("https://admin.googleapis.com/admin/directory/v1/users/{$email}", [
                        'password' => $password,
                        'changePasswordAtNextLogin' => false
                    ]);

                if ($googleUpdate->successful()) {
                    $request->status = 'approved';
                    $request->approve_by = $this->adminName;
                    $request->save();
                    return;
                }

                $request->status = 'queued';
                $request->save();
                return;
            }

            if ($googleCheck->status() == 404) {
                // Create user
                $googleCreate = Http::withToken($this->token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post('https://admin.googleapis.com/admin/directory/v1/users', [
                        'name' => [
                            'givenName' => $firstName,
                            'familyName' => $lastName
                        ],
                        'password' => $password,
                        'primaryEmail' => $email,
                        'orgUnitPath' => $orgUnit,
                        'changePasswordAtNextLogin' => false
                    ]);

                if ($googleCreate->successful()) {
                    $request->status = 'approved';
                    $request->approve_by = $this->adminName;
                    $request->save();
                    return;
                }

                $request->status = 'queued';
                $request->save();
                return;
            }

            // Other Google Check Error
            $request->status = 'queued';
            $request->save();

        } catch (\Throwable $e) {
            Log::error('Google approval error in Background Job', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            $request->status = 'queued';
            $request->save();
        }
    }
}
