<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $followingIds = auth()->user()->following()->pluck('users.id');
        $currentUser = auth()->user();

        $posts = Post::with('user', 'likes', 'comments.user')
            ->where('user_id', $currentUser->id)
            ->orWhereIn('user_id', $followingIds)
            ->latest()
            ->get()
            ->map(function ($post) use ($followingIds, $currentUser) {
                $post->user->is_following = $followingIds->contains($post->user_id);
                $post->likes_count = $post->likes()->count();
                $post->is_liked = $post->isLikedBy($currentUser);
                return $post;
            });

        return response()->json($posts);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $post = $this->postService->createPost($request->validated());

        return response()->json($post, 201);
    }

    public function getUnfollowedPosts()
    {
        $currentUser = auth()->user();
        $followingIds = $currentUser->following()->pluck('users.id')->push($currentUser->id);

        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->whereNotIn('user_id', $followingIds)
            ->latest()
            ->get()
            ->map(function ($post) use ($currentUser) {
                $post->likes_count = $post->likes()->count();
                $post->is_liked = $post->isLikedBy($currentUser);
                return $post;
            });

        return response()->json($posts);
    }
}
