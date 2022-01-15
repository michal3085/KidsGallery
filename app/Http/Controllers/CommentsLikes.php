<?php

namespace App\Http\Controllers;

use App\Models\CommentsLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsLikes extends Controller
{
    public function like($id)
    {

        if (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('like', 1)->count() == 0 &&
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('dislike', 1)->count() == 0) {

            $like = new CommentsLike();

            $like->comment_id = $id;
            $like->user_id = auth()->id();
            $like->like = 1;
            $like->dislike = 0;

            if ($like->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            }
        } elseif (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('like', 1)->count() == 0 &&
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('dislike', 1)->count() == 1) {

            $like = CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->first();
            $like->dislike = 0;
            $like->like = 1;

            if ($like->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            }

        } elseif (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('like', 1)->count() == 1) {
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->delete();
        }
    }

    public function dislike($id)
    {
        if (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('dislike', 1)->count() == 0 &&
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('like', 1)->count() == 0 ) {

            $like = new CommentsLike();

            $like->comment_id = $id;
            $like->user_id = auth()->id();
            $like->like = 0;
            $like->dislike = 1;

            if ($like->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            }
        } elseif (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('like', 1)->count() == 1 &&
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('dislike', 1)->count() == 0) {

            $dislike = CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->first();
            $dislike->dislike = 1;
            $dislike->like = 0;

            if ($dislike->save()) {
                return response()->json([
                    'status' => 'success'
                ]);
            }

        } elseif (CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->where('dislike', 1)->count() == 1) {
            CommentsLike::where('comment_id', $id)->where('user_id', auth()->id())->delete();
        }
    }
}
