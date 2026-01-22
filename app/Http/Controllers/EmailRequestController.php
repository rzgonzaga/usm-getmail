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
        | GET ACTIVE TERM
        |--------------------------------------------------------------------------
        */
        $activeTermId = null;

        $activeResponse = Http::get(
            'http://172.16.0.60/academic/api/v2/ActiveSemesters/active-only'
        );

        if ($activeResponse->successful()) {
            foreach ($activeResponse->json() as $item) {
                if (
                    $item['campusId'] == $campusId &&
                    $item['isActive'] === true
                ) {
                    $activeTermId = $item['termId'];
                    break;
                }
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
        | FINAL TERM CHECK (ACTIVE ≠ STUDENT TERM)
        |--------------------------------------------------------------------------
        */
        if (
            !isset($student['termId']) ||
            (int) $student['termId'] !== (int) $activeTermId
        ) {
            return back()->with([
                'message' => 'Not enrolled for the current semester.',
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
            return response()->json(['error' => 'Google access token missing. Please login again.'], 401);
        }

        $email = $request->email;
        $firstName = $request->firstname;
        $lastName = $request->lastname;
        $campusId = $request->campus_id;
        $password = $request->password; // Use existing password

        $orgUnit = match ($campusId) {
            1 => '/Main Campus/Students',
            3 => '/Kidapawan City Campus/Students',
            4 => '/Main Campus/Students/Graduate',
            default => '/'
        };

        try {
            // Check if user exists in Google
            $googleCheck = Http::withToken($token)
                ->get("https://admin.googleapis.com/admin/directory/v1/users/{$email}");

            if ($googleCheck->successful()) {
                // User exists → reset password
                $googleUpdate = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->put("https://admin.googleapis.com/admin/directory/v1/users/{$email}", [
                        'password' => $password
                    ]);

                $request->status = $googleUpdate->successful() ? 'approved' : 'rejected';
                if ($googleUpdate->successful()) {
                    $request->approve_by = auth()->user()->name;
                }
                $request->save();

            } elseif ($googleCheck->status() == 404) {
                // User does not exist → create account
                $googleCreate = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post('https://admin.googleapis.com/admin/directory/v1/users', [
                        'name' => [
                            'givenName' => $firstName,
                            'familyName' => $lastName
                        ],
                        'password' => $password,
                        'primaryEmail' => $email,
                        'orgUnitPath' => $orgUnit
                    ]);

                $request->status = $googleCreate->successful() ? 'approved' : 'rejected';
                if ($googleCreate->successful()) {
                    $request->approve_by = auth()->user()->name;
                }
                $request->save();
            } else {
                $request->status = 'rejected';
                $request->save();
            }

            return response()->json([
                'success' => true,
                'status' => $request->status,
                'email' => $email,
                'password' => $password
            ]);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Something went wrong. Check logs.'], 500);
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
