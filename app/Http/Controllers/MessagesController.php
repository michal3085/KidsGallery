<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        return view('messages.index')->with('conversations', auth()->user()->conversations());
    }

    public function show()
    {
      return view('messages.messages');
    }
}
