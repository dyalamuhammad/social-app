<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->input('post_id');

        Like::create([
            'post_id' => $post_id,
            'user_id' => $user_id,
        ]);

        return response()->json(['status' => 'liked']);
    }

    public function unlike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->input('post_id');

        Like::where('post_id', $post_id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json(['status' => 'unliked']);
    }
    public function countLikes($postId)
    {
        $likesCount = Like::where('post_id', $postId)->count();
        return response()->json(['likes_count' => $likesCount]);
    }
    public function checkLikeStatus($postId)
    {
        $isLike = Like::where('user_id', Auth::id())
            ->where('post_id', $postId)
            ->exists();

        return response()->json(['isLike' => $isLike]);
    }
}
