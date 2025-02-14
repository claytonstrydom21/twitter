<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with('user', 'likes', 'comments.user')
            ->latest()
            ->get()
            ->map(function ($post) use ($user) {
                $post->likes_count = $post->likes()->count();
                $post->is_liked = $post->isLikedBy(auth()->user());
                return $post;
            });

         return view('profile.show', [
            'user' => $user->loadCount(['posts', 'followers', 'following']),
            'posts' => $posts
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
        try {
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

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user->fresh()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
