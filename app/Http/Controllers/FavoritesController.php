<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function addToFavorites($picture_id)
    {
        $favorite = new Favorite();

        $favorite->user_id = Auth::id();
        $favorite->picture_id = $picture_id;

        if ( $favorite->save() ) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(400);
        }
    }

    public function removeFromFavorites($picture_id)
    {
       if ( Favorite::where('user_id', Auth::id())->where('picture_id', $picture_id)->delete() ) {
           return response()->json([
               'status' => 'success'
           ]);
       } else {
           return response()->json([
               'status' => 'error',
           ])->setStatusCode(400);
       }

    }
}
