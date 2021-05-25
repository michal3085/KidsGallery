<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        return view('messages.index')->with('conversations', auth()->user()->conversations());
    }

    public function show($to ,$id = NULL)
    {
        if (!isset($id)){
            if ( auth()->user()->conversationExist($to) == null ) {
                $new = new Conversation();
                $new->user_a = Auth::user()->name;
                $new->user_b = $to;
                $new->save();
                $id = $new->id;
            } elseif (auth()->user()->conversationExist($to) != null) {
                $check_id = auth()->user()->conversationExist($to);
                $id = $check_id->id;
            }
        }
        $messages = Message::where('conversation_id', $id)->latest()->paginate(10);
        $unreaded = Message::where('to_id', Auth::id())->where('from', $to)->get();

        foreach ($unreaded as $unread) {
            $unread->read = 1;
            $unread->save();
        }
        return view('messages.messages')->with(['messages' => $messages, 'conversation' => $id]);
    }

    public function add(Request $request)
    {
        $conversation = Conversation::where('id', $request->conversation)->first();

        $new_message = new Message();

        $new_message->from_id = $request->from;
        $new_message->conversation_id = $request->conversation;

        if ($conversation->user_a == Auth::user()->name) {
            $new_message->from = $conversation->user_a;
            $new_message->to = $conversation->user_b;
            $new_message->to_id = User::where('name', $conversation->user_b)->value('id');
        } else {
            $new_message->from = $conversation->user_b;
            $new_message->to = $conversation->user_a;
            $new_message->to_id = User::where('name', $conversation->user_a)->value('id');
        }
        $new_message->message = $request->message;
        $new_message->read = 0;

        if ($new_message->save()) {
            return redirect()->back();
        } else {
            return redirect()->back();
        }

    }
}
