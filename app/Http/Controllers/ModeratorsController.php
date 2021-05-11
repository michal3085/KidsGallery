<?php

namespace App\Http\Controllers;

use App\Models\ModeratorAction;
use App\Models\Picture;
use App\Models\PicturesReport;
use Illuminate\Support\Facades\Auth;

class ModeratorsController extends Controller
{
    public function index()
    {
        if ( auth()->user()->hasRole('admin') ) {
            $pictures = Picture::where('accept', 1)->latest()->paginate(10);
        } else {
            $pictures = Picture::where('accept', 1)->where('visible', 1)->latest()->paginate(10);
        }
        return view('moderator.index', compact('pictures'));
    }

    public function blockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 0;

        $mod_action = new ModeratorAction();
        $mod_action->moderator_id = Auth::id();
        $mod_action->user_id = $picture->user_id;
        $mod_action->action = __('The picture has been blocked');
        $mod_action->reason = __('Breaking the regulations');
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

    public function showBlocked()
    {
        $pictures = Picture::where('accept', 0)->latest()->paginate(10);

        return view('moderator.blockedpics', compact('pictures'));
    }

    public function showReportedPictures($id = NULL)
    {
        if (!isset($id)) {
            $reports = PicturesReport::with('pictures')->latest()->paginate(20);
        } else {
            $reports = PicturesReport::where('picture_id', $id)->latest()->paginate(20);
        }
        return view('moderator.reportedpics', compact('reports'));
    }

    public function reportDown($id)
    {
        $report = PicturesReport::where('id', $id)->first();
        $mod_action = new ModeratorAction();

        $mod_action->moderator_id = Auth::id();
        $mod_action->user_id = $report->picture_id;
        $mod_action->action = __('Moderator close report for this picture');
        $mod_action->reason = __('Other');
        $mod_action->type = "picture";
        $mod_action->type_id = $id;
        $mod_action->moderator_only = 1;

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
        $mod_action->reason = __('Other');
        $mod_action->type = "picture";
        $mod_action->type_id = $id;
        $mod_action->moderator_only = 1;

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
}
