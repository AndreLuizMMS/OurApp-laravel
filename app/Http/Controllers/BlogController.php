<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Mail\NewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BlogController extends Controller {

    // View --------------------------------------------------------------------------------------
    public function showEditPostView(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    public function showSinglePostView(Post $post, Request $request,) {
        $post['body'] = Str::markdown($post->body);
        return view('single-post', ['post' => $post]);
    }

    public function createPostView() {
        return view('create-post');
    }

    public function searchView($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username');
        return $posts;
    }

    // CRUD --------------------------------------------------------------------------------------
    public function updatePost(Post $post, Request $request) {
        $incomingFields = $request->validate([
            "title" => ['required'],
            "body" => ['required']
        ]);
        stripTags($incomingFields);

        $post->update($incomingFields);
        return redirect('/post/' . $post->id)->with('success', 'Post updated');
    }

    public function deletePost(Post $post) {
        $post->delete();
        return redirect("/profile/" . auth()->user()->username)->with('success', "Post deleted");
    }

    public function publishPost(Request $req) {
        $incomingFields = $req->validate([
            'title' => ['required'],
            'body' => ['required'],
        ]);
        stripTags($incomingFields);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'username' => auth()->user()->username,
            'title' => $newPost->title
        ]));
        return redirect("/post/{$newPost->id}")->with('success', "New post created");
    }
}

// Helper Functions --------------------------------------------------------------------------------------
function stripTags($arr) {
    foreach ($arr as $i) {
        $i = strip_tags($i);
    }
    return $arr;
}
