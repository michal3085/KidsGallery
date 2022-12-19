<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentsReport;
use App\Models\ModeratorAction;
use App\Models\Picture;
use App\Models\PicturesReport;
use App\Models\ReportedMessage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Promise\all;

class ModeratorsController extends Controller
{
    /*
     *  Shows gallery with moderator functions
     */
    public function index()
    {
        if ( auth()->user()->hasRole('admin') ) {
            $pictures = Picture::where('accept', 1)->latest()->paginate(10);
        } else {
            $allow = Auth::user()->followers()->where('rights', 1)->pluck('user_id');

            $all_pics = Picture::where('accept', 1)->where('visible', 1)->pluck('id');
            $allowed_pics = Picture::where('accept', 1)->where('visible', 0)->whereIn('user_id', $allow)->pluck('id');

            $ids = $all_pics->merge($allowed_pics);
            $pictures = Picture::whereIn('id', $ids)->latest()->paginate(20);
        }
        return view('moderator.index', compact('pictures'));
    }

    public function moderatorActions($open, $id = NULL)
    {
        if ( !isset($id) ) {
            $actions = ModeratorAction::where('moderator_id', Auth::id())
                ->where('moderator_viewed', 1)
                ->where('open', $open)
                ->latest()
                ->paginate(20);

            $new_actions = ModeratorAction::where('moderator_id', Auth::id())
                ->where('moderator_viewed', 0)
                ->where('open', $open)
                ->latest()
                ->paginate(20);
        } else{
            $actions = ModeratorAction::where('moderator_id', $id)->latest();
        }
        if ( Auth::user()->hasRole('admin') ) {
            $admin = 1;
        } else {
            $admin = 0;
        }

        return view('moderator.actions')->with([
            'admin' => $admin,
            'new_actions' => $new_actions,
            'actions' => $actions,
            'open' => $open
        ]);
    }

    public function showDetails($id)
    {
        $action = ModeratorAction::where('id', $id)->first();
        $action->moderator_viewed = 1;
        $action->save();

        if ( $action->type == 'picture' ) {
            $picture = Picture::where('id', $action->type_id)->first();
            $user = User::where('id', $action->user_id)->select('name', 'avatar')->first();
            $type = 'picture';

            return view('moderator.picview')->with([
                'picture' => $picture,
                'info' => $action,
                'user_name' => $user->name,
                'avatar' => $user->avatar,
                'action' => $action,
                'type' => $type
            ]);
        }
    }

    public function moderatorAnswer(Request $request, $id)
    {
        $action = ModeratorAction::where('id', $id)->first();
        $action->moderator_response = $request->answer;
        $action->moderator_viewed = 1;
        $action->user_viewed = 0;

        $action->save();
        return redirect()->back();
    }

    public function updateReason(Request $request, $id)
    {
        $reason = ModeratorAction::where('id', $id)->first();
        $reason->reason = $request->reason;
        $reason->save();
        return redirect()->back();
    }

    public function blockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 0;

        $mod_action = new ModeratorAction();
        $mod_action->moderator_id = Auth::id();
        $mod_action->user_id = $picture->user_id;
        $mod_action->action = 'The picture has been blocked';
        $mod_action->data = '';
        $mod_action->open = 1;
        $mod_action->reason = 'Breaking the regulations';
        $mod_action->type = "picture";
        $mod_action->type_id = $id;
        $mod_action->user_response = "";
        $mod_action->moderator_response = "";
        $mod_action->user_viewed = 0;
        $mod_action->moderator_viewed = 1;
        $mod_action->moderator_only = 0;

        $mod_action->save();


        if ($picture->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }

    public function reportedComments()
    {
        if (auth()->user()->hasRole('admin')) {
                $comments_ids = CommentsReport::latest()->pluck('comment_id');
                $comments = Comment::whereIn('id', $comments_ids)->latest()->paginate(5);

        } elseif (auth()->user()->hasRole('moderator')) {
            /*
             *  checking witch user comments i can watch, where commented picture has visible = 0
             */
            $allow = Auth::user()->followers()->where('rights', 1)->pluck('user_id');
            $allow_pics = Picture::where('visible', 0)->whereIn('user_id', $allow)->pluck('id');

            // getting id's of pictures where visible = 1
            $pics = Picture::where('visible', 1)->pluck('id');
            $result_ids = $pics->merge($allow_pics);

            $mod_comments = Comment::where('user_id', Auth::id())->pluck('user_id');

            $com = CommentsReport::whereIn('picture_id', $result_ids)->pluck('comment_id');
            $comments = Comment::whereIn('id', $com)->whereNotIn('user_id', $mod_comments)->latest()->paginate(5);
        }
        return view('moderator.reportedComments')->with(['comments' => $comments]);
    }

    public function deleteCommentReport($id)
    {
        if ( CommentsReport::where('comment_id', $id)->delete() ){
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    public function unblockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 1;

            if ($picture->save()) {
                ModeratorAction::where('moderator_id', Auth::id())
                    ->where('type_id', $id)
                    ->where('moderator_only', 0)
                    ->delete();
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                ])->setStatusCode(200);
            }

    }

    public  function saveMessage($id)
    {
        //
    }

    public function showBlocked()
    {
        if (auth()->user()->hasRole('admin')) {
            $pictures = Picture::where('accept', 0)->latest()->paginate(10);
        } else {
            $pictures = Picture::where('accept', 0)->where('visible', 1)->latest()->paginate(10);
        }
        return view('moderator.blockedpics', compact('pictures'));
    }

    public function showPicture($id)
    {
        if (auth()->user()->hasRole('admin')) {
            $picture = Picture::where('id', $id)->first();
            $comments = Comment::where('picture_id', $id)->latest()->paginate(20);

            return view('moderator.picture')->with(['picture' => $picture, 'comments' => $comments]);
        } else {
            $picture = Picture::where('id', $id)->first();
            $allow = Auth::user()->followers()->where('rights', 1)->pluck('user_id')->toArray();

            if ($picture->visible == 0 && in_array($picture->user_id, $allow) || $picture->visible == 1) {
                $picture = Picture::where('id', $id)->first();
                $comments = Comment::where('picture_id', $id)->latest()->paginate(20);

                return view('moderator.picture')->with(['picture' => $picture, 'comments' => $comments]);
            } elseif ($picture->visible == 0 && !in_array($picture->user_id, $allow)) {
                return redirect()->back();
            }
        }
    }

    public function showReportedPictures($id = NULL)
    {
        if (!isset($id)) {
            if ( auth()->user()->hasRole('admin') ) {
                $reports = PicturesReport::with('pictures')->latest()->paginate(20);
            } elseif ( auth()->user()->hasRole('moderator') ) {
                $without_pics = Picture::where('user_id', Auth::id())->pluck('id');
                $reports = PicturesReport::with('pictures')
                    ->whereNotIn('picture_id', $without_pics)
                    ->latest()
                    ->paginate(20);
            }
        } else {
            $reports = PicturesReport::where('picture_id', $id)->latest()->paginate(20);
        }
        return view('moderator.reportedpics', compact('reports'));
    }

    public function reportDown($id)
    {
        $report = PicturesReport::where('id', $id)->first();
        $mod_action = new ModeratorAction();
        $picture = Picture::where('id', $report->picture_id)->first();

        $mod_action->moderator_id = Auth::id();
        $mod_action->user_id = $picture->id;
        $mod_action->action = __('Moderator close report for this picture');
        $mod_action->data = '';
        $mod_action->open = 1;
        $mod_action->reason = __('Other');
        $mod_action->type = "close_pic";
        $mod_action->type_id = $report->picture_id;
        $mod_action->moderator_only = 1;
        $mod_action->user_viewed = 0;
        $mod_action->moderator_viewed = 1;

        $mod_action->save();

        if (PicturesReport::destroy($id)) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }

    public function reportDownAll($id)
    {
        $picture = Picture::where('id', $id)->first();

        $mod_action = new ModeratorAction();
        $mod_action->moderator_id = Auth::id();
        $mod_action->user_id = $picture->user_id;
        $mod_action->action = __('Moderator close all reports for this picture');
        $mod_action->data = '';
        $mod_action->open = 1;
        $mod_action->reason = __('Other');
        $mod_action->type = "close_pic";
        $mod_action->type_id = $id;
        $mod_action->moderator_only = 1;
        $mod_action->user_viewed = 0;
        $mod_action->moderator_viewed = 1;

        $mod_action->save();

        if ( PicturesReport::where('picture_id', $id)->delete() ) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }

    public function reportedMessages()
    {
        if ( auth()->user()->hasRole('admin') ) {
            $messages = ReportedMessage::latest()->paginate(20);
        } elseif ( auth()->user()->hasRole('moderator') ) {
            $without = ReportedMessage::where('from_id', Auth::id())->pluck('id');
            $messages = ReportedMessage::latest()->whereNotIn('id', $without)->paginate(20);
        }
        return view('moderator.reportedMessages', compact('messages'));
    }

    public function messageAccept($id)
    {
        if ( ReportedMessage::where('id', $id)->delete() ) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    public function allUsers()
    {
        $users = User::latest()->paginate(20);
        $mode = 1;

        return view('moderator.users', compact('users', 'mode'));
    }

    public function blockedUsers()
    {
        $users = User::where('active', 0)->latest()->paginate(20);
        $mode = 3;

        return view('moderator.users', compact('users', 'mode'));
    }

    public function activeUsers()
    {
        $users = User::where('active', 1)->latest()->paginate(20);
        $mode = 2;

        return view('moderator.users', compact('users', 'mode'));
    }

    public function moderatorList()
    {
        $moderators = Role::where('role', 'moderator')->pluck('user_id');

        $users = User::whereIn('id', $moderators)->where('active', 1)->latest()->paginate(20);
        $mode = 4;

        return view('moderator.users', compact('users', 'mode'));
    }

    public function blockUser($id)
    {
        $user = User::find($id);
        $user->active = 0;
        $user->save();

        return redirect()->back();
    }

    public function unblockUser($id)
    {
        $user = User::find($id);
        $user->active = 1;
        $user->save();

        return redirect()->back();
    }

    public function makeHimAModerator($id)
    {
        $moderator = new Role();
        $user = User::find($id);

        $moderator->user_id = $user->id;
        $moderator->role = 'moderator';
        $moderator->till = NULL;

        if ($moderator->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    public function deleteModerator($id)
    {
        if (Role::where('user_id', $id)->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    public function userSearch(Request $request, $mode)
    {
        if ($mode == 1) {
            $users = User::where('name', 'LIKE', "%$request->search%")->latest()->paginate(20);
        } elseif ($mode == 2) {
            $users = User::where('name', 'LIKE', "%$request->search%")->where('active', 1)->latest()->paginate(20);
        } elseif ($mode == 3) {
            $users = User::where('name', 'LIKE', "%$request->search%")->where('active', 0)->latest()->paginate(20);
        } elseif ($mode == 4) {
            $moderators = Role::all()->pluck('user_id');
            $users = User::where('name', 'LIKE', "%$request->search%")->whereIn('id', $moderators)->latest()->paginate(20);
        }
        return view('moderator.users', compact('users', 'mode'));
    }

    public function adminNew($id)
    {
        if (Auth::user()->hasRole('admin')) {
           $admin = new Role();
           $user = User::find($id);

            $admin->user_id = $user->id;
            $admin->role = 'admin';
            $admin->till = NULL;

            if ($admin->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                ])->setStatusCode(200);
            }
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    public function deleteAdmin($id)
    {
        if (Auth::user()->hasRole('admin')) {
           if ( Role::whereIn('role', ['admin'])->Where('user_id', $id)->delete() ) {
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                ])->setStatusCode(200);
            }
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(300);
        }
    }


}
