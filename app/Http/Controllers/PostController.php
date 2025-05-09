<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function showCreateForm() {
        return view('create-post');
    }

    public function createpost(Request $request) {
        $incomingFields = $request->validate([
            'title'=> 'required',
            'body'=> 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newpost = Post::create($incomingFields);

        return redirect("/post/{$newpost->id}")->with("success", 'New post successfully created.');
    }

    public function showSinglePost(Post $post) {
    return view('single-post',['post' => $post]);
    }
}
