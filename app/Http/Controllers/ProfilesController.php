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
    /*
     * other_user is the user whose profile we are viewing.
     */

    public function index($name)
    {
        $user = User::where('name', $name)->first();
        $pictures = Picture::where('user', $name)->where('accept', 1)->latest()->paginate(8);

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
        $user = User::where('name', $name)->first();
        $unwanted_pics = Picture::where('accept', 0)->pluck('id');
        $comments = Comment::where('user_name', $name)->whereNotIn('picture_id', $unwanted_pics)->latest()->paginate(20);

        return view('profiles.comments')->with(['comments' => $comments, 'other_user' => $user]);
    }

    public function favorites($name)
    {
        $user = User::where('name', $name)->first();
        $pictures = Picture::where('accept', 1)->whereHas('likes', function ($liked) use ($user) {
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
