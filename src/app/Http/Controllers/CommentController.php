<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Traits\SecurityHeaders;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use SecurityHeaders;
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        $response = response()->json($comment->load('user'));

        return $this->addSecurityHeaders($response);
    }

    public function destroy(Comment $comment)
    {
        if($comment->user_id !== auth()->id()){
            return response()->json(['message'=>'Unauthorized'], 403);
        }

        $comment->delete();
        $response = response()->json(['message'=>'Comment deleted']);

        return $this->addSecurityHeaders($response);
    }
}
