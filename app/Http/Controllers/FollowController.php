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

        $followingUser = User::find($followingId);
        $status = $followingUser->private ? 0 : 1;

    

        Follow::create([
            'follower_id' => $followerId,
            'following_id' => $followingId,
            'status' => $status,
        ]);

        return response()->json(['status' => 'followed', 'isfollow' => $status]);
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

    public function softDeleteFollow($id) {
        try {
            $follow = Follow::find($id); // Menggunakan find() karena primaryKey telah ditentukan
    
            $follow->delete();
            
           return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal ubah group. Error: ' . $e->getMessage());
        }
  
    }
}
