<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
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

    public function editPost(Request $request) {
        try {            
            $obj = Post::find($request->id);
            $obj->caption = $request->caption;
            $obj->save();
            return redirect()->back()
                ->with('success', 'Berhasil edit post');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal edit post. Error: ' . $e->getMessage());
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
}
