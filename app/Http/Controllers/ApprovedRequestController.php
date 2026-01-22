<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Models\EmailRequest;

class ApprovedRequestController extends Controller
{
    /**
     * Show email request form for users/admin.
     */
    public function index()
    {
        return view('admin.approved.index'); // your request form blade
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
        $query->where('status', 'approved');

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
            'recordsTotal' => EmailRequest::where('status', 'approved')->count(),
            'recordsFiltered' => $totalFiltered,
        ]);
    }
}
