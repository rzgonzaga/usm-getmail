<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Models\EmailRequest;

class EmailRequestController extends Controller
{
    /**
     * Show email request form for users/admin.
     */
    public function index()
    {
        return view('admin.request.index'); // your request form blade
    }

    /**
     * Store a new email request
     */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'campus' => 'required|integer',
            'student_no' => 'required|string|max:50',
            'cor_no' => 'required|string|max:50',
        ]);

        $campusId = $request->campus;
        $studentNo = $request->student_no;
        $corNo = $request->cor_no;

        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING REQUEST
        |--------------------------------------------------------------------------
        */
        $existing = EmailRequest::where('studentno', $studentNo)
            ->where('campus_id', $campusId)
            ->latest()
            ->first();

        if ($existing && $existing->status === 'approved') {
            return redirect()->route(
                'email.request.approved',
                Crypt::encrypt($existing->id)
            );
        }

        if ($existing) {
            return back()->with([
                'message' => 'Your request is pending for approval.',
                'requestSaved' => false
            ])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | GET CURRENT TERM ID (NEW API)
        |--------------------------------------------------------------------------
        */


        // $activeTermId = null;

        // $termResponse = Http::get(
        //     "http://172.16.0.60/academic/api/v2/sar/SarSettings/current-term-id/campus/{$campusId}"
        // );

        // if ($termResponse->successful() && isset($termResponse->json()['termId'])) {
        //     $activeTermId = $termResponse->json()['termId'];
        // }

        // if (!$activeTermId) {
        //     return back()->with([
        //         'message' => 'Unable to determine active semester.',
        //         'requestSaved' => false
        //     ])->withInput();
        // }


        $activeTermId = null;

        // Default term IDs based on campus
        if ($campusId == 1) {
            $activeTermId = 102;
        } elseif ($campusId == 3) {
            $activeTermId = 72;
        } else {
            $termResponse = Http::get(
                "http://172.16.0.60/academic/api/v2/sar/SarSettings/current-term-id/campus/{$campusId}"
            );

            if ($termResponse->successful() && isset($termResponse->json()['termId'])) {
                $activeTermId = $termResponse->json()['termId'];
            }
        }

        if (!$activeTermId) {
            return back()->with([
                'message' => 'Unable to determine active semester.',
                'requestSaved' => false
            ])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | FETCH STUDENT REGISTRATION
        |--------------------------------------------------------------------------
        */
        $studentResponse = Http::get(
            "http://172.16.0.60/academic/api/v2/Registrations/{$corNo}/get-student/{$studentNo}/term/{$activeTermId}?tenantId={$campusId}"
        );

        if (
            !$studentResponse->successful() ||
            empty($studentResponse->json()['student'])
        ) {
            return back()->with([
                'message' => 'No active enrollment found for the current semester. Please confirm your registration before proceeding.',
                'requestSaved' => false
            ])->withInput();
        }

        $student = $studentResponse->json()['student'];

        /*
       |--------------------------------------------------------------------------
       | CHECK IF EMAIL EXISTS
       |--------------------------------------------------------------------------
       */
        if (
            !isset($student['email']) ||
            empty(trim($student['email']))
        ) {
            return back()->with([
                'message' => 'No email address found for this student. Please contact the registrar.',
                'requestSaved' => false
            ])->withInput();
        }
        
        /*
        |--------------------------------------------------------------------------
        | GENERATE PASSWORD
        |--------------------------------------------------------------------------
        */
        $password =
            substr(str_shuffle('0123456789'), 0, 4)
            . 'MITUSM'
            . substr(str_shuffle('0123456789'), 0, 4);

        /*
        |--------------------------------------------------------------------------
        | SAVE REQUEST
        |--------------------------------------------------------------------------
        */
        EmailRequest::create([
            'campus_id' => $campusId,
            'studentno' => $studentNo,
            'firstname' => $student['firstName'] ?? null,
            'middlename' => $student['middlename'] ?? null,
            'lastname' => $student['lastName'] ?? null,
            'email' => $student['email'] ?? null,
            'status' => 'pending',
            'password' => $password,
        ]);

        return back()->with([
            'message' => 'Request submitted successfully.',
            'requestSaved' => true
        ]);
    }
    /**
     * Show approved request for a user
     */
    public function showApproved($encryptedId)
    {
        // Decrypt the ID
        $id = Crypt::decrypt($encryptedId);

        $request = EmailRequest::findOrFail($id);

        return view('approved', [
            'request' => $request
        ]);
    }
    /**
     * Reset password for approved request
     */
    public function resetPassword($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId); // decrypt the ID
        $request = EmailRequest::findOrFail($id);

        // Check if there's already a pending reset request for this student/email
        $pending = EmailRequest::where('studentno', $request->studentno)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return redirect()->route('email.request.approved', Crypt::encrypt($id))
                ->with('message', "You already have a pending reset request. Please wait until it is approved.");
        }

        // Generate new request instead of updating password
        EmailRequest::create([
            'campus_id' => $request->campus_id,
            'studentno' => $request->studentno,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'status' => 'pending',
            'password' => substr(str_shuffle("0123456789"), 0, 4) . "MITUSM" . substr(str_shuffle("0123456789"), 0, 4),
        ]);

        return redirect()->route('email.request.approved', Crypt::encrypt($id))
            ->with('message', "Your reset password request has been submitted. The old password is no longer valid.");
    }



    /**
     * Admin: Get all requests for datatable
     */
    public function getData(Request $request)
    {
        $columns = ['id', 'studentno', 'firstname', 'lastname', 'email', 'status'];

        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $order = $request->input('order.0.column', 0);
        $dir = $request->input('order.0.dir', 'asc');
        $search = $request->input('search.value');

        $query = EmailRequest::query();

        // Only fetch pending requests
        $query->where('status', 'pending');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('studentno', 'like', "%{$search}%")
                    ->orWhere('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $requests = $query->orderBy($columns[$order] ?? 'id', $dir)
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'data' => $requests,
            'recordsTotal' => EmailRequest::where('status', 'pending')->count(),
            'recordsFiltered' => $totalFiltered,
        ]);
    }


    /**
     * Admin: Approve request (optional)
     */
    public function approve($id)
    {
        $request = EmailRequest::findOrFail($id);

        $token = session('google_access_token'); // Google admin token

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Google access token missing. Please login again.'
            ], 401);
        }

        $email = trim($request->email);
        $firstName = $request->firstname;
        $lastName = $request->lastname;
        $campusId = $request->campus_id;
        $password = $request->password; // Use existing password

        if (empty($email)) {
            return response()->json([
                'success' => false,
                'error' => 'Email address is missing.'
            ], 422);
        }

        $orgUnit = match ((int) $campusId) {
            1 => '/Main Campus/Students',
            3 => '/Kidapawan City Campus/Students',
            4 => '/Main Campus/Students/Graduate',
            default => '/'
        };

        try {
            /*
            |--------------------------------------------------------------------------
            | CHECK IF USER EXISTS IN GOOGLE
            |--------------------------------------------------------------------------
            */
            $googleCheck = Http::withToken($token)
                ->get("https://admin.googleapis.com/admin/directory/v1/users/{$email}");

            /*
            |--------------------------------------------------------------------------
            | IF USER EXISTS, UPDATE PASSWORD
            |--------------------------------------------------------------------------
            */
            if ($googleCheck->successful()) {
                $googleUpdate = Http::withToken($token)
                    ->withHeaders([
                        'Content-Type' => 'application/json'
                    ])
                    ->put("https://admin.googleapis.com/admin/directory/v1/users/{$email}", [
                        'password' => $password,
                        'changePasswordAtNextLogin' => false
                    ]);

                if ($googleUpdate->successful()) {
                    $request->status = 'approved';
                    $request->approve_by = auth()->user()->name;
                    $request->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Existing Google account password updated successfully.',
                        'status' => $request->status,
                        'email' => $email,
                        'password' => $password
                    ]);
                }

                $request->status = 'rejected';
                $request->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Google account exists, but password update failed.',
                    'google_status' => $googleUpdate->status(),
                    'google_error' => $googleUpdate->json()
                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | IF USER DOES NOT EXIST, CREATE GOOGLE ACCOUNT
            |--------------------------------------------------------------------------
            */
            if ($googleCheck->status() == 404) {
                $googleCreate = Http::withToken($token)
                    ->withHeaders([
                        'Content-Type' => 'application/json'
                    ])
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
                    $request->approve_by = auth()->user()->name;
                    $request->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Google account created successfully.',
                        'status' => $request->status,
                        'email' => $email,
                        'password' => $password
                    ]);
                }

                $request->status = 'rejected';
                $request->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Google account creation failed.',
                    'google_status' => $googleCreate->status(),
                    'google_error' => $googleCreate->json()
                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | OTHER GOOGLE CHECK ERROR
            |--------------------------------------------------------------------------
            */
            $request->status = 'rejected';
            $request->save();

            return response()->json([
                'success' => false,
                'message' => 'Unable to check Google account.',
                'google_status' => $googleCheck->status(),
                'google_error' => $googleCheck->json()
            ], 500);

        } catch (\Throwable $e) {
            \Log::error('Google approval error', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Check logs.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Reject request (optional)
     */
    public function reject($id)
    {
        $request = EmailRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
