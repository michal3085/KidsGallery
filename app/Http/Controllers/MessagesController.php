<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
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

    public function unreadIndex()
    {
        $ids = Message::where('to_id', Auth::id())->where('read', 0)->pluck('conversation_id');
        $conversations = Conversation::whereIn('id', $ids)->latest()->paginate(15);

        return view('messages.unread')->with('conversations', $conversations);
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', "%$request->search%")->latest()->paginate(8);

        return view('messages.search')->with('users', $users);
    }

    public function show($to ,$id = NULL)
    {
        /*
         * if no id is given, check if the converse exists.
         *  If it does not we create it, and if it exists we
         *  take id of this conversation.
         */
        if (!isset($id)) {
            if ( auth()->user()->conversationExist($to) == null ) {
                $new = new Conversation();
                $new->user_a = Auth::user()->name;
                $new->user_b = $to;
                $new->save();
                $id = $new->id;
            } else {
                $check_id = auth()->user()->conversationExist($to);
                $id = $check_id->id;
            }
        }
        /*
         * I check if the user belongs to a conversation.
         */
        $check = Conversation::where('id', $id)->first();

        if ($check->user_a == Auth::user()->name || $check->user_b == Auth::user()->name) {
            /*
             * I retrieve the messages from a conversation.
             */
            $messages = Message::where('conversation_id', $id)->latest()->paginate(15);
            $unreaded = Message::where('to_id', Auth::id())->where('from', $to)->get();

            /*
             * I'm checking to see if the other user has blocked us.
             */
            if (BlockedUser::where('user_id', User::where('name', $to)->value('id'))->where('blocks_user', Auth::id())->count() != 0) {
                $not_allow = 1;
            } else {
                $not_allow = 0;
            }

            /*
             * Set the messages as read for that user.
             */
            foreach ($unreaded as $unread) {
                $unread->read = 1;
                $unread->save();
            }
            return view('messages.messages')->with(['messages' => $messages,
                'conversation' => $id,
                'not_allow' => $not_allow
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function send(Request $request)
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
