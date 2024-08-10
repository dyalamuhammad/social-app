<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $followerId = Auth::id();
        $followingId = $request->input('following_id');

        Follow::create([
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);

        return response()->json(['status' => 'followed']);
    }

    public function unfollow(Request $request)
    {
        $followerId = Auth::id();
        $followingId = $request->input('following_id');

        Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->delete();

        return response()->json(['status' => 'unfollowed']);
    }
}
