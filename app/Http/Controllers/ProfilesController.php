<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\Picture;
use App\Models\User;
use App\Models\UsersData;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{

    public function index($name)
    {
        $user = User::where('name', $name)->first();

        if ( $user->id == Auth::id() ) {
            $pictures = Picture::where('user', $name)->where('accept', 1)->latest()->paginate(8);
        } else {
            $pictures = Picture::where('user', $name)->where('accept', 1)->latest()->paginate(8);
        }

        return view('profiles.index')->with(['pictures' => $pictures, 'other_user' => $user]);
    }

    public function info($name)
    {
        $user = User::where('name', $name)->first();

        if ( UsersData::where('user_id', $user->id)->count() == 0 ) {
            $userdata_create = new UsersData();
            $userdata_create->user_id = $user->id;
            $userdata_create->save();
        }

        $user_data = UsersData::where('user_id', $user->id)->first();

        return view('profiles.info')->with(['other_user' => $user, 'userdata' => $user_data]);
    }

    public function comments($name)
    {
        $comments = Comment::where('user_name', $name)->latest()->paginate(20);
        $user = User::where('name', $name)->first();

        return view('profiles.comments')->with(['comments' => $comments, 'other_user' => $user]);
    }

    public function favorites($name)
    {
        $user = User::where('name', $name)->first();
        $pictures = Picture::whereHas('likes', function ($liked) use ($user) {
            $liked->where('user_id', $user->id);
        })->latest()->paginate(20);

        return view('profiles.favorites')->with(['pictures' => $pictures, 'other_user' => $user]);
    }

    public function following($name)
    {
        $user = User::where('name', $name)->first();
        $followers = $user->following()->latest()->paginate(30);

        return view('profiles.following')->with(['other_user' => $user, 'followers' => $followers]);
    }

    public function followers($name)
    {
        $user = User::where('name', $name)->first();
        $followers = $user->followers()->latest()->paginate(30);

        return view('profiles.followers')->with(['other_user' => $user, 'followers' => $followers]);
    }
}
