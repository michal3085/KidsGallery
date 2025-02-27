<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ReportedMessage;
use App\Models\UnwantedConversation;
use App\Models\User;
use App\Models\UsersData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        $ids = Message::where('to_id', Auth::id())->where('read', 0)->pluck('conversation_id');
        $unread = Conversation::whereIn('id', $ids)->latest()->paginate(15);
        $unwanted = auth()->user()->unwantedConversation()->pluck('conversation_id');
        $not_show = $unwanted->merge($ids);

        // Check which conversations have no messages and delete them
        $conv_check = auth()->user()->conversations()->whereNotIn('id', $not_show)->pluck('id');
        foreach ($conv_check as $value) {
            if (Message::countMessages($value) == 0) {
                Conversation::where('id', $value)->delete();
            }
        }

        $unfollow_msg = UsersData::where('user_id', Auth::id())->pluck('unfollowing_msg')->first();

       return view('messages.index')
           ->with([
               'conversations' => auth()->user()->conversations()->whereNotIn('id', $not_show),
               'unread' => $unread,
               'unfollowing_msg' => $unfollow_msg
           ]);
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', "%$request->search%")->latest()->paginate(8);

        return view('messages.search')->with(['users' => $users, 'search' => $request->search]);
    }

    public function show($to ,$id = NULL)
    {
        /*
         *  if no id is given, check if the converse exists.
         *  If it does not we create it, and if it exists we
         *  take id of this conversation.
         */
        if (!isset($id)) {
            if (auth()->user()->conversationExist($to) == null) {
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
            } elseif (UnwantedConversation::where('conversation_id', $id)->where('user_id', '!=', Auth::id())->count() != 0) {
                $not_allow = 2;
            } elseif (BlockedUser::where('user_id', Auth::id())->where('blocks_user', User::where('name', $to)->value('id'))->count() != 0) {
                $not_allow = 3;
            } elseif ( Auth::user()->shouldIWriteToHim($to) == 0) {
                $not_allow = 4;
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
            return view('messages.messages')->with([
                'messages' => $messages,
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
            if (Auth::user()->shouldIWriteToHim($conversation->user_b) == 0) {
                return redirect()->back();
            }
            $new_message->from = $conversation->user_a;
            $new_message->to = $conversation->user_b;
            $new_message->to_id = User::where('name', $conversation->user_b)->value('id');
        } else {
            if (Auth::user()->shouldIWriteToHim($conversation->user_a) == 0) {
                return redirect()->back();
            }
            $new_message->from = $conversation->user_b;
            $new_message->to = $conversation->user_a;
            $new_message->to_id = User::where('name', $conversation->user_a)->value('id');
        }
        $new_message->message = $request->message;
        $new_message->read = 0;
        $new_message->from_ip = $request->ip();
        $new_message->deleted = 0;

        if ($new_message->save()) {
            // touch() update a update_at in conversation table.
            $conversation->touch();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function reportMessage($id)
    {
        $message = Message::where('id', $id)->first();
        $report = new ReportedMessage();

        $report->conversation_id = $message->conversation_id;
        $report->message_id = $id;
        $report->from_name = $message->from;
        $report->message = $message->message;
        $report->from_id = $message->from_id;
        $report->from_ip = $message->from_ip;

        if ( ReportedMessage::where('message_id', $id)->count() == 0 ){
            $report->save();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }

    public function delete($id)
    {
        $message = Message::where('id', $id)->first();

        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator')) {
            $message->message = "[Wiadomość usunięta przez moderatora " . Auth::user()->name . "]";
            $message->deleted = 1;
            ReportedMessage::where('message_id', $id)->delete();
        } else {
            $message->message = "[Wiadomość usunięta przez autora]";
            $message->deleted = 1;
        }
        $message->timestamps = false;

        if ( $message->save() ){
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }

    public function unwantedConversation($id)
    {
        if (UnwantedConversation::where('conversation_id', $id)->where('user_id', Auth::id())->count() == 0) {
            $unwanted = new UnwantedConversation();

            $unwanted->conversation_id = $id;
            $unwanted->user_id = Auth::id();
            $unwanted->user = Auth::user()->name;

            if ($unwanted->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                ])->setStatusCode(400);
            }
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }

    public function nowIwantConversation($id)
    {
        if (UnwantedConversation::where('conversation_id', $id)->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }

    public function reciveSwitcher()
    {
        $msgs = UsersData::where('user_id', Auth::id())->first();

        if ($msgs->unfollowing_msg == 1){
            $msgs->unfollowing_msg = 0;
        } else {
            $msgs->unfollowing_msg = 1;
        }

        if ( $msgs->save() ) {
            return response()->json([
                'status' => 'success'
            ]);
        }  else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }
}
