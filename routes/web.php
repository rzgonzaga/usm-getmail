<?php

use App\Http\Controllers\ApprovedRequestController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\EmailRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RejectedRequestController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.pending.index');
    }
    
    $campuses = App\Models\CampusTerm::orderBy('campus_name')->get();
    return view('welcome', compact('campuses'));
})->name('welcome');

// Google OAuth routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// 🔐 Authenticated routes
Route::middleware('auth')->group(function () {

    // Pending
    Route::get('/admin/pending', [EmailRequestController::class, 'index'])->name('admin.pending.index');
    Route::get('/admin/pending/data', [EmailRequestController::class, 'getData'])->name('admin.pending.data');
    Route::post('/admin/pending/{id}/approve', [EmailRequestController::class, 'approve'])->name('admin.pending.approve');
    Route::post('/admin/pending/{id}/reject', [EmailRequestController::class, 'reject'])->name('admin.pending.reject');
    // Approved
    Route::get('/admin/approved', [ApprovedRequestController::class, 'index'])->name('admin.approved.index');
    Route::get('/admin/approved/data', [ApprovedRequestController::class, 'getData'])->name('admin.approved.data');

    // Rejected
    Route::get('/admin/rejected', [RejectedRequestController::class, 'index'])->name('admin.rejected.index');
    Route::get('/admin/rejected/data', [RejectedRequestController::class, 'getData'])->name('admin.rejected.data');

    // Queued
    Route::get('/admin/queued', [App\Http\Controllers\QueuedRequestController::class, 'index'])->name('admin.queued.index');
    Route::get('/admin/queued/data', [App\Http\Controllers\QueuedRequestController::class, 'getData'])->name('admin.queued.data');
    
    // Background Queue Approve All
    Route::post('/admin/requests/approve-all', [EmailRequestController::class, 'approveAllPending']);
    Route::post('/admin/requests/approve-all-queued', [EmailRequestController::class, 'approveAllQueued']);

    // Campus Terms
    Route::get('/admin/campus-terms', [App\Http\Controllers\Admin\CampusTermController::class, 'index'])->name('admin.campus_terms.index');
    Route::post('/admin/campus-terms', [App\Http\Controllers\Admin\CampusTermController::class, 'store'])->name('admin.campus_terms.store');
    Route::get('/admin/campus-terms/fetch-terms', [App\Http\Controllers\Admin\CampusTermController::class, 'fetchTerms'])->name('admin.campus_terms.fetch');
    Route::put('/admin/campus-terms/{id}', [App\Http\Controllers\Admin\CampusTermController::class, 'update'])->name('admin.campus_terms.update');
    Route::delete('/admin/campus-terms/{id}', [App\Http\Controllers\Admin\CampusTermController::class, 'destroy'])->name('admin.campus_terms.destroy');

    // (Optional) Dashboard – keep only if still needed
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/email-request', [EmailRequestController::class, 'store'])->name('email.request.store');
Route::get('/approved/{id}', [EmailRequestController::class, 'showApproved'])->name('email.request.approved');
Route::post('/reset-password/{id}', [EmailRequestController::class, 'resetPassword'])->name('email.request.reset');

// Auth scaffolding routes
require __DIR__ . '/auth.php';
