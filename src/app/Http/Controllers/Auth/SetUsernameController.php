<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SetUsernameController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function show()
    {
        return view('auth.set-username');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:users,username',
                    'alpha_dash'
                ]
            ]);

            $this->authService->setUsername($validated['username']);

            return response()->json(['redirect' => route('home')]);
        } catch (ValidationException $e) {
            $usernameErrors = $e->errors()['username'] ?? [];

            $errorMessage = collect($usernameErrors)
                ->first(fn($error) => str_contains($error, 'already been taken'))
                ?? $e->getMessage();

            return response()->json([
                'message' => $errorMessage === $e->getMessage()
                    ? $errorMessage
                    : 'This username has already been taken',
                'field' => 'username'
            ], 422);
        }
    }

    public function generateSuggestions()
    {
        $suggestions = $this->authService->generateUsernameSuggestions(auth()->user()->name);
        return response()->json($suggestions);
    }
}
