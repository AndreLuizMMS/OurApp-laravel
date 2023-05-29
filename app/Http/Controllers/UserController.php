<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {




    public function logout() {
        auth()->logout();
        return redirect("/")->with('success', 'User logged out!');
    }

    public function showCorrectHomepage() {
        if (auth()->check()) {
            return view('hompage-feed');
        }
        return view('homepage');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);

        auth()->login($user);
        return redirect('/')->with('success', 'Resgister success');
    }

    public function login(Request $request) {
        $incomingFields = $request->validate([
            "loginusername" => ['required'],
            "loginpassword" => ['required'],
        ]);

        $auth = auth()->attempt([
            "username" => $incomingFields['loginusername'],
            "password" => $incomingFields['loginpassword']
        ]);

        if ($auth) {
            $request->session()->regenerate();
            return redirect("/")->with('success', 'User logged in !');
        } else {
            return redirect("/")->with('failed', 'Invalid login');
        }
        return redirect("/");
    }

    public function profile(User $user) {
        $userPosts =  $user->posts()->get();

        return view(
            'profile-posts',
            [
                'username' => $user->username,
                'posts' => $userPosts
            ]
        );
    }
}
