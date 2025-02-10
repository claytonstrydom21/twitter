<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    use SecurityHeaders;
    public function users(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->input('query');

        $followingIds = auth()->user()->following()->pluck('users.id');

        $users = User::query()
        ->where('id', '!=', auth()->id())
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->latest()
            ->take(10)
            ->get();

        $users = $users->map(function ($user) use ($followingIds) {
                $user->is_following = $followingIds->contains($user->id);
                return $user;
            });

        $response = response()->json($users);

        return $this->addSecurityHeaders($response);
    }
}
