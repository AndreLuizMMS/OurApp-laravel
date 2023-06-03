<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller {
    public function loginApi(Request $req) {
        $incomingFields = $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first();
            $token = $user->createToken('ourapptoken');
            return $token;
        }
        return 'Invalid credentials';
    }

    public function publishPostApi(Request $req) {
        $incomingFields = $req->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        stripTags($incomingFields);
        // $incomingFields['user_id'] = auth()->id();
        $incomingFields['user_id'] = 1;
        $newPost = Post::create($incomingFields);

        return $newPost->id;
    }
}

function stripTags($arr) {
    foreach ($arr as $i) {
        $i = strip_tags($i);
    }
    return $arr;
}
