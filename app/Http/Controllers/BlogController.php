<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

function stripTags($arr) {
    foreach ($arr as $i) {
        $i = strip_tags($i);
    }
    return $arr;
}

class BlogController extends Controller {

    public function updatePost(Post $post, Request $request) {
        $incomingFields = $request->validate([
            "title" => ['required'],
            "body" => ['required']
        ]);
        stripTags($incomingFields);

        $post->update($incomingFields);
        return redirect('/post/' . $post->id)->with('success', 'Post updated');
    }

    public function showEditPost(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    public function delete(Post $post) {
        $post->delete();
        return redirect("/profile/" . auth()->user()->username)->with('success', "Post deleted");
    }

    public function showSinglePost(Post $post, Request $request,) {
        $post['body'] = Str::markdown($post->body);
        return view('single-post', ['post' => $post]);
    }

    public function createPost() {
        return view('create-post');
    }

    public function publishPost(Request $req) {
        $incomingFields = $req->validate([
            'title' => ['required'],
            'body' => ['required'],
        ]);

        stripTags($incomingFields);
        $incomingFields['user_id'] = auth()->id();

        $postId = Post::create($incomingFields)->id;
        return redirect("/post/{$postId}")->with('success', "New post created");
    }
}
