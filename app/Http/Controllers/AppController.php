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
use Illuminate\Support\Facades\Hash;
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
    
    public function explore()
    {
        $user = Auth::user();
        $title = "SocialApp";
        $users = User::whereNot('id', $user->id)->get();
        return view('explore', compact('users', 'user', 'title'));
    }
    
    public function notification()
    {
        $user = Auth::user();
        $title = "SocialApp";
        $follows = Follow::where('following_id', $user->id)->get();
        $users = User::whereNot('id', $user->id)->get();
        return view('notification', compact('users', 'user', 'title', 'follows'));
    }
   
    public function acceptFollower(Request $request)
    {
        $follow = Follow::find($request->id);
        $follow->status = 1;
        $follow->save();

        return response()->json(['success' => true]);
    }
    public function editPassword(Request $request) {
    
            $obj = User::find($request->id);
             if (!Hash::check($request->current_password, $obj->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai.');
        }
          
                $obj->password = Hash::make($request->new_password);
                $obj->save();
                return redirect()->back()
                    ->with('success', 'Berhasil edit password'); 
    }
}
