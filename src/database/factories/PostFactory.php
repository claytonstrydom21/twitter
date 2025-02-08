<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->sentences(2, true),
            'image_path' => null
        ];
    }

    public function withImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'image_path' => 'posts/test-image.jpg'
            ];
        });
    }
}
