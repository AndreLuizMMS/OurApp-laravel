<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller {
    public function followUser(User $user) {
        if ($user->id === auth()->user()->id) {
            return back()->with("failed", "Cannot follow yourself");
        }

        $alreadyFollows = Follow::where([
            ['user_id', '=', auth()->user()->id],
            ['followedUser', '=', $user->id]
        ])->count();
        if ($alreadyFollows) {
            return back()->with("failed", "You already follow that user");
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followedUser = $user->id;
        $newFollow->save();

        return back()->with("success", "User followed");
    }

    public function unfollowUser(User $user) {
        Follow::where([
            ['user_id', '=', auth()->user()->id],
            ['followedUser', '=', $user->id]
        ])->delete();

        return back()->with('success', "User unfollowed");
    }
}
