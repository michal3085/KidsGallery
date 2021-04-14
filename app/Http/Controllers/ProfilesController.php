<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Picture;
use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{

    public function index($name)
    {
        $pictures = Picture::where('user', $name)->latest()->paginate(5);
        $user = User::where('name', $name)->get();

        return view('profiles.index')->with(['pictures' => $pictures, 'other_user' => $user]);
    }

    public function comments($name)
    {
        $comments = Comment::where('user_name', $name)->latest()->paginate(20);
        $user = User::where('name', $name)->get();

        return view('profiles.comments')->with(['comments' => $comments, 'other_user' => $user]);
    }
}
