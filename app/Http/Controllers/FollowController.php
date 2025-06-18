<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {
        // user don't follow himself
        if ($user->id == auth()->user()->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // user cannot follow a user more than 1 time
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id] , ['followeduser', '=', $user->id]])->count();

        if ($existCheck) {
            return back()->with('error', 'You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'User successfully followed');
    }

    public function removeFollow(User $user) {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
        return back()->with('success', 'User successfully unfollowed.');
    }
}
