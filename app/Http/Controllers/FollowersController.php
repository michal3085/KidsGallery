<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function addFollower($id)
    {
        if ( Follower::where('follow_id', $id)->where('user_id', Auth::id())->count() == 0 && Auth::id() != $id ) {
            $follower = new Follower();
            $follower->user_id = Auth::id();
            $follower->follow_id = $id;
            $follower->rights = 0;

            $follower->save();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function deleteFollower($id)
    {
       if ( Follower::where('user_id', Auth::id())->where('follow_id', $id)->delete() ) {
           return response()->json([
               'status' => 'success'
           ]);
       } else {
           return response()->json([
               'status' => 'error',
           ])->setStatusCode();
       }

    }

    public function addRights($id)
    {
            $follower = Follower::where('user_id', Auth::id())->where('follow_id', $id)->first();
            $follower->rights = 1;

            $follower->save();

            return response()->json([
                'status' => 'success'
            ]);
    }

    public function deleteRights($id)
    {
        $follower = Follower::where('user_id', Auth::id())->where('follow_id', $id)->first();
        $follower->rights = 0;

        if ( $follower->save() ) {
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
