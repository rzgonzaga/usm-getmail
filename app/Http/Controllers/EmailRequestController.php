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
            'campus' => 'required|string',
            'student_no' => 'required|string|max:50',
            'cor_no' => 'required|string|max:50',
        ]);

        // The campus field now contains the primary key of the CampusTerm model
        $campusTermId = $request->campus;
        $studentNo = $request->student_no;
        $corNo = $request->cor_no;

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

        // Fetch term ID from the database if configured
        $campusTerm = \App\Models\CampusTerm::find($campusTermId);
        
        if (!$campusTerm) {
            return back()->with([
                'message' => 'Invalid campus selected.',
                'requestSaved' => false
            ])->withInput();
        }

        $campusId = $campusTerm->campus_id;
        $apiTenantId = $campusTerm->tenant_id ?? $campusId;
        
        if ($campusTerm && $campusTerm->term_id) {
            $activeTermId = $campusTerm->term_id;
        } else {
            // Fallback to API if not set in DB
            $termResponse = \Illuminate\Support\Facades\Http::get(
                "http://172.16.0.60/academic/api/v2/sar/SarSettings/current-term-id/campus/{$apiTenantId}"
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
        $studentResponse = \Illuminate\Support\Facades\Http::get(
            "http://172.16.0.60/academic/api/v2/Registrations/{$corNo}/get-student/{$studentNo}/term/{$activeTermId}?tenantId={$apiTenantId}"
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
        | CHECK EXISTING REQUEST
        |--------------------------------------------------------------------------
        */
        $existing = EmailRequest::where('studentno', $studentNo)
            ->where('campus_id', $campusTermId)
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
            'campus_id' => $campusTermId,
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

        $requests = $query->with('campusTerm')->orderBy($columns[$order] ?? 'id', $dir)
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
     * Admin: Approve All Pending requests using Jobs
     */
    public function approveAllPending(Request $request)
    {
        $token = session('google_access_token'); // Google admin token

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Google access token missing. Please login again.'
            ], 401);
        }

        // Get all pending requests
        $pendingRequests = EmailRequest::where('status', 'pending')->get();

        if ($pendingRequests->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No pending requests to approve.'
            ]);
        }

        $adminName = auth()->user()->name;

        foreach ($pendingRequests as $req) {
            // Dispatch to queue
            \App\Jobs\ProcessEmailRequestJob::dispatch($req->id, $token, $adminName);
        }

        return response()->json([
            'success' => true,
            'message' => 'All pending requests have been queued for background processing.'
        ]);
    }

    /**
     * Admin: Approve All Queued requests using Jobs
     */
    public function approveAllQueued(Request $request)
    {
        $token = session('google_access_token'); // Google admin token

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Google access token missing. Please login again.'
            ], 401);
        }

        // Get all queued requests
        $queuedRequests = EmailRequest::where('status', 'queued')->get();

        if ($queuedRequests->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No queued requests to retry.'
            ]);
        }

        $adminName = auth()->user()->name;

        foreach ($queuedRequests as $req) {
            // Dispatch to queue
            \App\Jobs\ProcessEmailRequestJob::dispatch($req->id, $token, $adminName);
        }

        return response()->json([
            'success' => true,
            'message' => 'All queued requests have been scheduled for background processing.'
        ]);
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
