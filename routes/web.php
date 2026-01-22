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
    return auth()->check()
        ? redirect()->route('admin.pending.index')
        : view('welcome');
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
