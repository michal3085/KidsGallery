<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\ModeratorAction;
use App\Models\Picture;
use App\Models\User;
use App\Models\UsersData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{

    public function index($name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id == Auth::id()) {
            $ids = Picture::where('user_id', Auth::id())->pluck('id');
        } else {
            $pictures = Picture::where('user', $name)->where('visible', 1)->pluck('id');

            // checking who's letting us see their hidden photos
            $friends = Auth::user()->followers()->where('rights', 1)->pluck('user_id');

            // im taking id's of these pictures
            $hidden_pictures = Picture::where('visible', 0)
                ->where('user', $name)
                ->whereIn('user_id', $friends)->pluck('id');

            $ids = $pictures->merge($hidden_pictures);
        }
        $pictures = Picture::where('accept', 1)->whereIn('id', $ids)->latest()->paginate(8);

        return view('profiles.index')->with(['pictures' => $pictures, 'other_user' => $user]);
    }

    public function info($name)
    {
        $user = User::where('name', $name)->first();
        $actions = ModeratorAction::where('user_id', Auth::id())->where('moderator_only', 0)->latest()->paginate(20);

        return view('profiles.info')->with(['actions' => $actions, 'other_user' => $user]);
    }

    public function about($name)
    {
        $user = User::where('name', $name)->first();

        if ( UsersData::where('user_id', $user->id)->count() == 0 ) {
            $userdata_create = new UsersData();
            $userdata_create->user_id = $user->id;
            $userdata_create->save();
        }
        $user_data = UsersData::where('user_id', $user->id)->first();

        return view('profiles.about')->with(['other_user' => $user, 'userdata' => $user_data]);
    }

    public function bannedPictureShow($picture_id, $mod_info, $name)
    {
        $picture = Picture::where('id', $picture_id)->first();
        $info = ModeratorAction::where('id', $mod_info)->where('type_id', $picture_id)->first();
        $user = User::where('name', $name)->first();

        if ($info->user_id == Auth::id()) {
            $info->user_viewed = 1;
            $info->moderator_viewed = 0;
            $info->save();
            return view('profiles.banned_picture')->with(['picture' => $picture, 'info' => $info, 'other_user' => $user]);
        } else {
            return redirect()->back();
        }
    }


    public function banAnswer(Request $request)
    {
        $info = ModeratorAction::where('id', $request->info)->first();

        $info->user_response = $request->user_answer;
        $info->user_viewed = 1;
        $info->moderator_viewed = 0;

        $info->save();
        return redirect()->back()->with('message', 'Wiadomość wysłana');
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
        $followers = $user->following()->latest()->paginate(20);

        return view('profiles.following')->with(['other_user' => $user, 'followers' => $followers]);
    }

    public function followers($name)
    {
        $user = User::where('name', $name)->first();
        $followers = $user->followers()->latest()->paginate(20);

        return view('profiles.followers')->with(['other_user' => $user, 'followers' => $followers]);
    }
}
