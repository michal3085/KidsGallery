<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentsLike;
use App\Models\CommentsReport;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $comments = new Comment();
        $picture = Picture::where('id', $id)->first();

        $comments->picture_id = $id;
        $comments->user_id = Auth::id();
        $comments->comment = $request->comment;
        $comments->user_name = Auth::user()->name;
        $comments->parent_id = $picture->user_id;
        $comments->save();

        return redirect()->back();
    }

    public function report($id)
    {
        if (CommentsReport::where('comment_id', $id)->where('user_id', Auth::id())->count() == 0) {
            $report = new CommentsReport();
            $picture_id = Comment::where('id', $id)->first();

            $report->user_id = Auth::id();
            $report->comment_id = $id;
            $report->picture_id = $picture_id->picture_id;
            $report->save();

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ], 400);
        }
    }

    public function destroy($id)
    {
        if ( Comment::destroy($id) ){
            CommentsLike::where('comment_id', $id)->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
