<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile.show', [
            'user' => $user,
            'posts' => $user->posts()->with('user')->latest()->get()
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . auth()->id()],
        ]);

        auth()->user()->update($validated);

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
