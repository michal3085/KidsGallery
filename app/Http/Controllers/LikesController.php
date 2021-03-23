<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function getLike($id)
    {
        $pictures = Picture::find($id);
        $like = new like();

        $count = $pictures->likes()->where('picture_id', $id)->where('user_id', Auth::id())->count();

        if ($count == 0){
            $like->user_id = Auth::id();
            $like->picture_id = $id;
            $like->liked = 1;

            $like->save();

            // temporary until i create a better top10 system.
            $pictures->likes = $pictures->likes  + 1;
            $pictures->save();

            return response('OK');

        } else {
            return response('Juz polubione');
        }

    }

//    public function likesCount($id)
//    {
//        $likes = like::where('picture_id', $id)->count();
//        return $likes;
//    }

}
