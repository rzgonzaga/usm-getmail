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

            $email = $googleUser->getEmail();
            $accessToken = $googleUser->token;

            // 🔥 Check if admin
            if (! $this->isGoogleAdmin($email, $accessToken)) {
                return redirect()->route('login')
                    ->with('error', 'Access denied. You must be an admin.');
            }

            // ✅ Create or update user
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            // Optional token
            $apiToken = $user->createToken('google-login')->plainTextToken;

            session([
                'google_access_token' => $accessToken,
                'api_token' => $apiToken,
            ]);

            return redirect()->route('admin.pending.index');

        } catch (\Throwable $e) {

            // 🔥 LOG FULL ERROR
            \Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Google login failed. Contact administrator.');
        }
    }

    private function isGoogleAdmin(string $email, string $accessToken): bool
    {
        try {
            $response = Http::withToken($accessToken)
                ->get(
                    'https://admin.googleapis.com/admin/directory/v1/users/' . urlencode($email)
                );

            if (! $response->successful()) {
                \Log::error('Google Admin API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            // 🔥 IMPORTANT: field is "isAdmin"
            return $response->json('isAdmin') === true;

        } catch (\Throwable $e) {

            \Log::error('Admin Check Exception', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}