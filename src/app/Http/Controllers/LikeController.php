<?php

namespace App\Http\Controllers;

use App\Jobs\CreateLikeNotificationJob;
use App\Models\Post;
use App\Traits\SecurityHeaders;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    use SecurityHeaders;

    public function like(Post $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $alreadyLiked = $post->likes()->where('user_id', auth()->id())->exists();

            if ($alreadyLiked) {
                return $this->addSecurityHeaders(
                    response()->json([
                        'likes_count' => $post->likes()->count(),
                        'status' => 'already_liked'
                    ])
                );
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

            $response = response()->json([
                'likes_count' => $post->likes()->count(),
                'status' => 'liked'
            ]);

            return $this->addSecurityHeaders($response);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Like action failed: ' . $e->getMessage());

            return $this->addSecurityHeaders(
                response()->json([
                    'error' => 'Unable to process like',
                    'message' => $e->getMessage()
                ], 500)
            );
        }
    }

    public function unlike(Post $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $post->likes()->detach(auth()->id());
            DB::commit();

            $response = response()->json([
                'likes_count' => $post->likes()->count(),
                'status' => 'unliked'
            ]);

            return $this->addSecurityHeaders($response);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Unlike action failed: ' . $e->getMessage());

            return $this->addSecurityHeaders(
                response()->json([
                    'error' => 'Unable to process unlike',
                    'message' => $e->getMessage()
                ], 500)
            );
        }
    }
}
