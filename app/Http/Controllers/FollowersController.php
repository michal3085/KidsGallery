<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function addFollower($id)
    {
        if ( Follower::where('follower_id', $id)->where('user_id', Auth::id())->count() == 0 && Auth::id() != $id ) {
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
       if ( Follower::where('user_id', Auth::id())->where('follower_id', $id)->delete() ) {
           return response()->json([
               'status' => 'success'
           ]);
       } else {
           return response()->json([
               'status' => 'error',
           ])->setStatusCode();
       }

    }
}
