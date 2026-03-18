<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/admin.directory.user.readonly',
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Using stateless() is recommended for APIs or specific redirect flows
            $googleUser = Socialite::driver('google')->stateless()->user();
            $email = $googleUser->getEmail();
            $accessToken = $googleUser->token;

            // 1. Security Check: Must be a USM email
            if (!Str::endsWith($email, '@usm.edu.ph')) {
                return redirect()->route('login')
                    ->with('error', 'Access denied. Please use your @usm.edu.ph account.');
            }

            // 2. Admin Check: Calls Google Admin SDK
            if (!$this->isGoogleAdmin($email, $accessToken)) {
                return redirect()->route('login')
                    ->with('error', 'Access denied. You do not have Administrative privileges.');
            }

            // 3. User Sync: Create or Update in local DB
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)), // Placeholder password
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            // 4. Session Management
            session(['google_access_token' => $accessToken]);

            return redirect()->route('admin.pending.index');

        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again.');
        }
    }

    private function isGoogleAdmin(string $email, string $accessToken): bool
    {
        try {
            $response = Http::withToken($accessToken)
                ->get("https://admin.googleapis.com/admin/directory/v1/users/{$email}");

            if ($response->failed()) {
                Log::warning("Admin SDK lookup failed for {$email}: " . $response->body());
                return false;
            }

            // Returns true only if 'isAdmin' property is true in Google Workspace
            return $response->json('isAdmin') === true;

        } catch (\Exception $e) {
            Log::error("Admin Check Exception: " . $e->getMessage());
            return false;
        }
    }
}