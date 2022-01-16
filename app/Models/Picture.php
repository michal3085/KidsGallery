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
}
