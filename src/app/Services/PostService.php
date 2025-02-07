<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function createPost(array $data): Post
    {
        $postData = [
            'content' => $data['content']
        ];

        if(isset($data['image'])) {
            $path = $data['image']->store('posts', 'public');
            $postData['image_path'] = $path;
        }

        $post = Auth::user()->posts()->create($postData);

        return $post->load('user');
    }
}
