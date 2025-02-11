<?php

namespace App\Http\Controllers;

use App\Jobs\CreateFollowNotificationJob;
use App\Models\User;
use App\Traits\SecurityHeaders;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    use SecurityHeaders;
    public function follow(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        if (!$currentUser->following()->where('following_id', $user->id)->exists()) {

            $currentUser->following()->attach($user->id);

            CreateFollowNotificationJob::dispatch(
                $user->id,
                $currentUser->id
            );

            $response = response()->json(['message' => 'Successfully followed user']);
            return $this->addSecurityHeaders($response);
        }
        return response()->json(['message' => 'You are already following this user'], 422);
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
