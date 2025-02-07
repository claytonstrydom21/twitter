<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/posts/{$post->id}/like");

        $response->assertStatus(200);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    public function test_a_user_can_unlike_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // First like the post
        $user->likes()->attach($post->id);

        $response = $this->actingAs($user)
            ->deleteJson("/posts/{$post->id}/like");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }
}
