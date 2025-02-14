<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_can_update_their_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'username' => 'updated_username',
                'email' => 'updated@email.com'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'username' => 'updated_username',
            'email' => 'updated@email.com'
        ]);
    }

    public function test_user_can_update_profile_with_avatar()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'username' => 'updated_username3',
                'email' => 'updated@email.com',
                'avatar' => $file
            ]);

        $response->assertStatus(200);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());

        $updatedUser = User::find($user->id);
        $this->assertNotNull($updatedUser->avatar);
    }

    public function test_a_user_cannot_update_to_existing_email()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user1)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'email' => $user2->email,
                'username' => 'updated_username2'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_a_user_cannot_update_to_existing_username()
    {
        $user1 = User::factory()->create(['username' => 'username1']);
        $user2 = User::factory()->create(['username' => 'username2']);

        $response = $this->actingAs($user1)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'email' => $user1->email,
                'username' => 'username2'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }
}
