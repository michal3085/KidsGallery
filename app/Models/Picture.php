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
// to delete
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
}
