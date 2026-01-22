<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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
                'https://www.googleapis.com/auth/admin.directory.user',
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $googleAccessToken = $googleUser->token;

            // Check if user is Google Workspace admin
            if (! $this->isGoogleAdmin($googleUser->getEmail(), $googleAccessToken)) {
                // Redirect back with a friendly error message
                return redirect()->route('login')
                    ->with('error', 'Access denied. You must be an admin to log in.');
            }

            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            // Optional: create Laravel API token
            $apiToken = $user->createToken('google-login')->plainTextToken;

            // Store tokens in session if needed
            session([
                'google_access_token' => $googleAccessToken,
                'api_token' => $apiToken,
            ]);

            return redirect()->route('admin.pending.index');

        } catch (\Throwable $e) {
            // Optional: log error for debugging
            \Log::error('Google login error: '.$e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Unable to login using Google. Please try again.');
        }
    }

    private function isGoogleAdmin(string $email, string $accessToken): bool
    {
        $response = Http::withToken($accessToken)
            ->get("https://admin.googleapis.com/admin/directory/v1/users/{$email}");

        // Successful response and user is admin
        return $response->successful() && $response->json('isAdmin') === true;
    }
}
