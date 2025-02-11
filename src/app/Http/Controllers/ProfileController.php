<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use SecurityHeaders;
    public function show(User $user)
    {
         return $response = view('profile.show', [
            'user' => $user->loadCount(['posts', 'followers', 'following']),
            'posts' => $user->posts()->with('user')->latest()->get()
        ]);
    }

    public function edit()
    {
        return $response = view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . auth()->id()],
            'avatar' => ['nullable', 'image', 'max:5120']
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            if($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return $response = response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }
}
