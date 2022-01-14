<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsLike extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->belongsTo('App\Models\Comment');
    }
}
