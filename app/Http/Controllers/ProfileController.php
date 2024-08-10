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

    // post
    public function storePost(Request $request) {
        $obj = new Post();
        $obj->user_id = $request->user_id;
        $obj->img = $request->img;
        $obj->caption = $request->caption;
        
        if ($request->img) {
            date_default_timezone_set('Asia/Jakarta');
            $imageName = date('dmYHis') . '.' .$request->img->extension();
            $imagePath = 'post/';
            $request->img->move(public_path($imagePath), $imageName);
            $imgPath = 'post/' . $imageName;
    
            $obj->img = $imgPath;
        }

        $obj->save();
    }
    public function doValidate($request) {
        $model = [
            'user_id' => 'required',
            'img' => 'required',
            'caption' => 'required',
        ];
           
        $request->validate($model);
    }
    public function post(Request $request) {
        try {
            $this->doValidate($request);     
            $this->storePost($request);
    
            return redirect()->back()
                ->with('success', 'Berhasil menambahkan post');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan post. Error: ' . $e->getMessage());
        }
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

    public function softDelete(Request $request) {
        try {
            $obj = Post::where('id', $request->id)->first();
            $obj->delete();
        return redirect()->back()
                ->with('success', 'Berhasil delete post');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal delete post. Error: ' . $e->getMessage());
        }
    }

    // comment
    public function storeComment(Request $request) {
        $obj = new Comment();
        $obj->post_id = $request->post_id;
        $obj->user_id = $request->user_id;
        $obj->desc = $request->desc;  

        $obj->save();
    }
    public function doValidateComment($request) {
        $model = [
            'post_id' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
        ];
           
        $request->validate($model);
    }
    public function comment(Request $request) {
        try {
            $this->doValidateComment($request);     
            $this->storeComment($request);
    
            return redirect()->back()
                ->with('success', 'Berhasil menambahkan comment');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan comment. Error: ' . $e->getMessage());
        }
    }
}
