<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CampusTerm;

class CampusTermController extends Controller
{
    public function index()
    {
        $campusTerms = CampusTerm::orderBy('campus_id')->get();
        
        $apiTerms = [];
        foreach ($campusTerms as $term) {
            $apiTenantId = $term->tenant_id ?? $term->campus_id; // fallback to campus_id if tenant_id is null
            $apiTerms[$term->campus_id] = \Illuminate\Support\Facades\Cache::remember('terms_tenant_'.$apiTenantId, 3600, function () use ($apiTenantId) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(5)
                        ->get("http://172.16.0.60/academic/api/v2/AyTermConfigs/list?tenantId={$apiTenantId}");
                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Exception $e) {
                    // Ignore errors
                }
                return [];
            });
        }

        return view('admin.campus_terms.index', compact('campusTerms', 'apiTerms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'campus_id' => 'required|integer',
            'campus_name' => 'required|string|max:255',
            'tenant_id' => 'nullable|integer',
            'term_id' => 'nullable|integer',
        ]);

        $campusTerm = CampusTerm::findOrFail($id);

        // Handle potential duplicate campus_id
        $newCampusId = $request->campus_id;
        $existing = CampusTerm::where('campus_id', $newCampusId)->where('id', '!=', $id)->first();
        if (!$existing) {
            $campusTerm->campus_id = $newCampusId;
        }

        $campusTerm->campus_name = $request->campus_name;
        $campusTerm->term_id = $request->term_id;
        $campusTerm->tenant_id = $request->tenant_id;
        $campusTerm->save();

        return back()->with('success', 'Campus term updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|integer|unique:campus_terms,campus_id',
            'campus_name' => 'required|string|max:255',
            'tenant_id' => 'nullable|integer',
            'term_id' => 'nullable|integer',
        ]);

        CampusTerm::create($request->all());

        return back()->with('success', 'New campus added successfully.');
    }

    public function destroy($id)
    {
        $campusTerm = CampusTerm::findOrFail($id);
        $campusTerm->delete();

        return back()->with('success', 'Campus deleted successfully.');
    }

    public function fetchTerms(Request $request)
    {
        $tenantId = $request->query('tenant_id');
        if (!$tenantId) {
            return response()->json([]);
        }

        $terms = \Illuminate\Support\Facades\Cache::remember('terms_tenant_'.$tenantId, 3600, function () use ($tenantId) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->get("http://172.16.0.60/academic/api/v2/AyTermConfigs/list?tenantId={$tenantId}");
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                // Ignore errors
            }
            return [];
        });

        return response()->json($terms);
    }
}
