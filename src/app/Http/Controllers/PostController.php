<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Traits\SecurityHeaders;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use SecurityHeaders;
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

        $response = response()->json($posts);

        return $this->addSecurityHeaders($response);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $post = $this->postService->createPost($request->validated());

        $response = response()->json($post, 201);

        return $this->addSecurityHeaders($response);
    }

    public function getUnfollowedPosts()
    {
        $followingIds = auth()->user()->following()->pluck('users.id')->push(auth()->id());

        Log::info('User ID:', ['id' => auth()->id()]);
        Log::info('Following IDs:', ['ids' => $followingIds->toArray()]);

        $posts = Post::with('user')
            ->whereNotIn('user_id', $followingIds)
            ->latest()
            ->get();

        Log::info('Posts:', ['posts' => $posts->toArray()]);

        $response = response()->json($posts);

        return $this->addSecurityHeaders($response);
    }
}
