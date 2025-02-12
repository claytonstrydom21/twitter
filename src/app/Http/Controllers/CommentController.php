<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        return response()->json($comment->load('user'));
    }

    public function destroy(Comment $comment)
    {
        if($comment->user_id !== auth()->id()){
            return response()->json(['message'=>'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message'=>'Comment deleted']);
    }
}
