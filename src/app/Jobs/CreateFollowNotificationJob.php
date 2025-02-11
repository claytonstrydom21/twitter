<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateFollowNotificationJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;

    protected $followedUserId;
    protected $followerUserId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $followedUserId, int $followerUserId)
    {
        $this->followedUserId = $followedUserId;
        $this->followerUserId = $followerUserId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $existingNotification = Notification::where([
            'user_id' => $this->followedUserId,
            'sender_id' => $this->followerUserId,
            'type' => 'follow',
            'is_read' => false
        ])->first();

        if ($existingNotification) {
            return;
        }

        Notification::create([
            'user_id' => $this->followedUserId,
            'sender_id' => $this->followerUserId,
            'type' => 'follow',
            'content' => $this->generateNotificationContent(),
            'notifiable_type' => User::class,
            'notifiable_id' => $this->followedUserId,
            'is_read' => false
        ]);
    }

    protected function generateNotificationContent(): string
    {
        $follower = User::findOrFail($this->followerUserId);
        return "{$follower->username} started following you.";
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Follow Notification Job Failed: ' . $exception->getMessage());
    }
}
