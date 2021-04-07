<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $comments = new Comment();

        $comments->picture_id = $id;
        $comments->comment = $request->comment;
        $comments->user_name = Auth::user()->name;

        $comments->save();
        return redirect()->back();
    }

    public function report($id)
    {
        if (CommentsReport::where('comment_id', $id)->where('user_id', Auth::id())->count() == 0) {

            $report = new CommentsReport();

            $report->user_id = Auth::id();
            $report->comment_id = $id;
            $report->save();

            return response()->json([
                'status' => 'success'
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Juz zes to reportowal'
            ]);
        }
    }

    public function destroy($id)
    {
        if ( Comment::destroy($id) ){
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
