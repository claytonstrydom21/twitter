<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateLikeNotificationJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;

    protected $postOwnerId;
    protected $likerId;
    protected $postId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $postOwnerId, int $likerId, int $postId)
    {
        $this->postOwnerId = $postOwnerId;
        $this->likerId = $likerId;
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $existingNotification = Notification::where([
            'user_id' => $this->postOwnerId,
            'sender_id' => $this->likerId,
            'type' => 'like',
            'notifiable_type' => Post::class,
            'notifiable_id' => $this->postId,
            'is_read' => false
        ])->first();

        if($existingNotification) {
            return;
        }

        Notification::create([
            'user_id' => $this->postOwnerId,
            'sender_id' => $this->likerId,
            'type' => 'like',
            'content' => $this->generateNotificationContent(),
            'notifiable_type' => Post::class,
            'notifiable_id' => $this->postId,
            'is_read' => false
        ]);
    }

    protected function generateNotificationContent(): string
    {
        $liker = User::findOrFail($this->likerId);
        return "{$liker->username} liked your post";
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Like Notification Job Failed: ' .$exception->getMessage());
    }
}
