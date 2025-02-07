<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_follow_another_user()
    {
        $user = User::factory()->create();
        $userToFollow = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/users/{$userToFollow->id}/follow");

        $response->assertStatus(200);

        $this->assertDatabaseHas('followers', [
            'follower_id' => $user->id,
            'following_id' => $userToFollow->id
        ]);
    }

    public function test_a_user_can_unfollow_another_user()
    {
        $user = User::factory()->create();
        $userToUnfollow = User::factory()->create();

        $user->following()->attach($userToUnfollow->id);

        $response = $this->actingAs($user)
            ->deleteJson("/users/{$userToUnfollow->id}/follow");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('followers', [
            'follower_id' => $user->id,
            'following_id' => $userToUnfollow->id
        ]);
    }

    public function test_user_can_see_followed_users_posts_in_feed()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        //user 1 creates post
        Post::factory()->create([
            'user_id' => $user1->id,
            'content' => 'User 1 post'
        ]);

        //user 2 creates post
        Post::factory()->create([
            'user_id' => $user2->id,
            'content' => 'User 2 post'
        ]);

        $response = $this->actingAs($user2)
            ->getJson('/posts');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.content', 'User 2 post');

        $this->actingAs($user2)
            ->postJson("/users/{$user1->id}/follow");

        $response = $this->actingAs($user2)
            ->getJson('/posts');

        $response->assertStatus(200)
            ->assertJsonCount(2);

        $this->actingAs($user2)
            ->deleteJson("/users/{$user1->id}/follow");

        $response = $this->actingAs($user2)
            ->getJson('/posts');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.content', 'User 2 post');
    }
}
