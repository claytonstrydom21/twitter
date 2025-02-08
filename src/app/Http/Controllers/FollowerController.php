<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    public function follow(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        if(auth()->user()->following()->where('following_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are already following this user'], 422);
        }

        auth()->user()->following()->attach($user->id);
        return response()->json(['message' => 'Successfully followed user']);
    }

    public function unfollow(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot unfollow yourself'], 422);
        }

        auth()->user()->following()->detach($user->id);
        return response()->json(['message' => 'Successfully unfollowed user']);
    }
}
