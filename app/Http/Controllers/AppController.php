<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AppController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $user = Auth::user();
        $following = Follow::where('follower_id', $user->id)->value('following_id');
        $post = Post::whereIn('user_id', [$user->id, $following])->orderBy('updated_at', 'desc')->get();
        $title = 'SocialApp';
        $comments = Comment::all();
        return view('dashboard',compact('user', 'post', 'comments', 'title'));
    }
    public function getComments($postId)
    {
        $comments = Comment::where('post_id', $postId)->get();

        $commentsData = $comments->map(function ($comment) {
            $user = User::find($comment->user_id);
            return [
                'user_img' => $user->img ? asset($user->img) : asset('blank-profile.jpg'),
                'user_name' => $user->name,
                'desc' => $comment->desc,
            ];
        });

        return response()->json(['comments' => $commentsData]);
    }
    public function explore()
    {
        $user = Auth::user();
        $title = "SocialApp";
        $users = User::whereNot('id', $user->id)->get();
        return view('explore', compact('users', 'user', 'title'));
    }

    
}
