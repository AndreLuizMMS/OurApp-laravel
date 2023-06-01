<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    private function getSharedData(User $user) {
        $alreadyFollows = 0;
        if (auth()->check()) {
            $alreadyFollows = Follow::where([
                ['user_id', '=', auth()->user()->id],
                ['followedUser', '=', $user->id]
            ])->count();
        }

        View::share(
            'sharedData',
            [
                'user' => $user,
                'alreadyFollows' => $alreadyFollows,
                'postCount' => count($user->posts()->get()),
                'followersCount' => $user->followers()->get()->count(),
                'followingCount' => $user->isFollowing()->get()->count()
            ]
        );
    }

    // View --------------------------------------------------------------------------------------
    public function homePageView() {
        if (auth()->check()) {
            return view('hompage-feed');
        }
        return view('homepage');
    }

    public function profileView(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->get()]);
    }

    public function profileFollowersView(User $user,) {
        $this->getSharedData($user);
        return view('profile-followers', ['userFollowers' => $user->followers()->get()]);
    }

    public function profileFollowingView(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['userFollows' => $user->isFollowing()->get()]);
    }

    // CRUD --------------------------------------------------------------------------------------
    public function manageAvatarView() {
        return view('avatar-form');
    }

    public function storeNewAvatar(Request $req) {
        $req->validate([
            'avatar' => 'required|image|max:5000'
        ]);
        $user = auth()->user();

        $resizedImg = Image::make($req->file('avatar'))->fit(170)->encode('jpg');
        $fileName = $user->username . uniqid() . '.jpg';
        Storage::put('public/avatars/' . $fileName, $resizedImg);

        if ($user->avatar !== 'default.png') { // delete old avatar
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $user->avatar = $fileName;
        $user->save();

        return redirect('/profile/' . $user->username)->with('success', "Avatar changed!");
    }


    // User Actions --------------------------------------------------------------------------------------
    public function logoutUser() {
        auth()->logout();
        return redirect("/")->with('success', 'User logged out!');
    }

    public function registerUser(Request $request) {
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

    public function loginUser(Request $request) {
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
        }
        return redirect("/")->with('failed', 'Invalid login');
    }
}
