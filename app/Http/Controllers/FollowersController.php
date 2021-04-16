<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function addFollower($id)
    {
        if ( Follower::where('follower_id', $id)->where('user_id', Auth::id())->count() == 0 ) {
            $follower = new Follower();
            $follower->user_id = Auth::id();
            $follower->follower_id = $id;
            $follower->rights = 0;

            $follower->save();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function deleteFollower($id)
    {
        // ...
    }
}
