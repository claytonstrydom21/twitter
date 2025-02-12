<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_comment_on_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/posts/{$post->id}/comments", [
                'content' => 'This is a comment'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a comment'
        ]);
    }

    public function test_a_user_cannot_comment_on_a_post_without_content()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/posts/{$post->id}/comments", [
                'content' => ''
            ]);

        $response->assertStatus(422);
    }
}
