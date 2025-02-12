<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_notification_when_followed()
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $response = $this->actingAs($follower)
            ->postJson("/users/{$user->id}/follow");

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'follow',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'is_read' => false
        ]);
    }

    public function test_user_receives_notification_when_a_post_is_liked()
    {
        $user = User::factory()->create();
        $liker = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($liker)
            ->postJson("/posts/{$post->id}/like");

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'like',
            'notifiable_type' => Post::class,
            'notifiable_id' => $post->id,
            'is_read' => false
        ]);
    }
}
