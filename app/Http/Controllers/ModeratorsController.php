<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;

class ModeratorsController extends Controller
{
    public function index()
    {
        $pictures = Picture::where('accept', 1)->latest()->paginate(10);

        return view('moderator.index', compact('pictures'));
    }

    public function blockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 0;
        $picture->save();

        return redirect()->back();
    }

    public function unblockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 1;
        $picture->save();

        return redirect()->back();
    }

    public function showBlocked()
    {
        $pictures = Picture::where('accept', 0)->latest()->paginate(10);

        return view('moderator.blockedpics', compact('pictures'));
    }
}
