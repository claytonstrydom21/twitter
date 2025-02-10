<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SecurityHeaders;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    use SecurityHeaders;
    public function follow(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        if(auth()->user()->following()->where('following_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are already following this user'], 422);
        }

        auth()->user()->following()->attach($user->id);
        $response = response()->json(['message' => 'Successfully followed user']);

        return $this->addSecurityHeaders($response);
    }

    public function unfollow(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot unfollow yourself'], 422);
        }

        auth()->user()->following()->detach($user->id);
        $response = response()->json(['message' => 'Successfully unfollowed user']);

        return $this->addSecurityHeaders($response);
    }
}
