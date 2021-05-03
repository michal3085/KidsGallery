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
}
