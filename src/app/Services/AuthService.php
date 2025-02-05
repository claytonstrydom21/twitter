<?php

namespace App\Services;

use App\Exceptions\UsernameUpdateException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return $user;
    }

    public function setUsername(string $username)
    {
        try {
            if (User::where('username', $username)->exists()) {
                throw new UsernameUpdateException('This username is already taken. Please choose another one.');
            }

            $updated = auth()->user()->update([
                'username' => $username
            ]);

            if (!$updated) {
                throw new UsernameUpdateException('Failed to update username');
            }

            return true;
        } catch (UsernameUpdateException $e) {
            Log::error('Username update error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error setting username: ' . $e->getMessage());
            throw new UsernameUpdateException(
                'An unexpected error occurred. Please try again.',
                0,
                $e
            );
        }
    }

    public function generateUsernameSuggestions(string $name): array
    {
        return collect(range(1, 5))->map(function () use ($name) {
            return Str::lower(str_replace(' ', '', $name) . rand(1000, 9999));
        })->toArray();
    }
}
