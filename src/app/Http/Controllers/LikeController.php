<?php

namespace App\Http\Controllers;

use App\Jobs\CreateLikeNotificationJob;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{

    public function like(Post $post): JsonResponse
    {
        DB::beginTransaction();

        try {
            if ($post->likes()->where('user_id', auth()->id())->exists()) {
                return $this->likeResponse($post, 'already_liked');
            }

            $post->likes()->attach(auth()->id());

            if ($post->user_id !== auth()->id()) {
                CreateLikeNotificationJob::dispatch(
                    $post->user_id,
                    auth()->id(),
                    $post->id
                );
            }

            DB::commit();
            return $this->likeResponse($post, 'liked');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Unable to process like', $e);
        }
    }

    public function unlike(Post $post): JsonResponse
    {
        DB::beginTransaction();

        try {
            $post->likes()->detach(auth()->id());

            DB::commit();
            return $this->likeResponse($post, 'unliked');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Unable to process unlike', $e);
        }
    }

    private function likeResponse(Post $post, string $status): JsonResponse
    {
        return response()->json([
            'likes_count' => $post->likes()->count(),
            'status' => $status
        ]);
    }

    private function errorResponse(string $error, \Exception $exception): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'message' => $exception->getMessage()
        ], 500);
    }
}
