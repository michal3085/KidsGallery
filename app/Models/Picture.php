<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = ["name", "file_path", "created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\like');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function commentsreports()
    {
        return $this->hasMany('App\Models\CommentReport');
    }

    public function pictureViews()
    {
        return $this->hasOne('App\Models\PictureView');
    }

    public function isTopLikes($id)
    {
        $check = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('likes')->take(10)->pluck('id')->toArray();
        if ( in_array($id, $check)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function isTopViews($id)
    {
        $check = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('views')->take(10)->pluck('id')->toArray();
        if ( in_array($id, $check)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function witchPlaceInViews($id)
    {
        $result = 0;
        $check = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('views')->take(10)->pluck('id')->toArray();
        foreach ($check as $key => $value) {
            if ($value == $id){
                $result = $key + 1;
            }
        }
        return $result;
    }

    public function witchPlaceInLikess($id)
    {
        $result = 0;
        $check = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('likes')->take(10)->pluck('id')->toArray();
        foreach ($check as $key => $value) {
            if ($value == $id){
                $result = $key + 1;
            }
        }
        return $result;
    }

    public function pictureLikesCount($id)
    {
        return like::where('picture_id', $id)->count();
    }
}
