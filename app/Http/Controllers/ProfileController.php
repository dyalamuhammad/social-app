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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, $id)
    {
        $user = Auth::user();
        $users = User::where('id', $id)->first();
        $title = $users->name;
        $post = Post::where('user_id', $id)->orderBy('updated_at', 'desc')->get();
        $following = Follow::where('follower_id', $id)->get();
        $follower = Follow::where('following_id', $id)->get();
        return view('profile.index', compact('user','users', 'post', 'following', 'follower', 'title'));
    }    

    public function updateProfile(Request $request) {
        try { 
            $obj = User::where('id', $request->id)->first();
            if($request->name) {
                $obj->name = $request->name;
            } else {
                $obj->name;
            }
            $obj->email;
            $obj->password;
            if($request->img) {
                $obj->img = $request->img;
            } else {
                $obj->img;
            }
            if($request->bio) {
                $obj->bio = $request->bio;
            } else {
                $obj->bio;
            }
    
            if ($request->img) {
                date_default_timezone_set('Asia/Jakarta');
                $imageName = date('dmYHis') . '.' .$request->img->extension();
                $imagePath = 'profile/';
                $request->img->move(public_path($imagePath), $imageName);
                $imgPath = 'profile/' . $imageName;
        
                $obj->img = $imgPath;
            }
    
            $obj->save();
        return redirect()->back()
                ->with('success', 'Berhasil update profile');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update profile. Error: ' . $e->getMessage());
        }
    }

    public function togglePrivate(Request $request)
    {
        try {

            $user = Auth::user();
            $obj = User::find($user->id);
            $obj->private = $request->private;
            $obj->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update profile. Error: ' . $e->getMessage());
        }
    }

    public function post(Request $request, $id)
    {
        $user = Auth::user();
        $users = User::where('id', $id)->first();
        $title = 'Posts';
        $post = Post::where('user_id', $id)->orderBy('updated_at', 'desc')->get();
        $following = Follow::where('follower_id', $id)->get();
        $follower = Follow::where('following_id', $id)->get();
        return view('profile.post', compact('user','users', 'post', 'following', 'follower', 'title'));
    }  

    
}
