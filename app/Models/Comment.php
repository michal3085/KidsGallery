<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function pictures()
    {
        return $this->belongsTo('App\Models\Picture');
    }

    public function commentslikes()
    {
        return $this->hasMany('App\Models\CommentLike');
    }
}
