<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/posts', [
                'content' => 'This is a test post'
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'content' => 'This is a test post',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'content' => 'This is a test post',
            'user_id' => $user->id
        ]);
    }

    public function test_a_user_can_create_a_post_with_an_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->actingAs($user)
            ->postJson('/posts', [
                'content' => 'Post with image',
                'image' => $file
            ]);

        $response->assertStatus(201);

        Storage::disk('public')->assertExists('posts/' . $file->hashName());

        $this->assertDatabaseHas('posts', [
            'content' => 'Post with image',
            'user_id' => $user->id,
            'image_path' => 'posts/' . $file->hashName()
        ]);
    }

    public function test_a_user_gets_posts_on_their_feed()
    {
        $user = User::factory()->create();

        // Create some posts
        Post::factory(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
            ->getJson('/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'content',
                    'created_at',
                    'updated_at',
                    'user' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
    }
}
