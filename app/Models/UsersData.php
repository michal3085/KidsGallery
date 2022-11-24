<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersData extends Model
{
    use HasFactory;
    protected $fillabe = ['user_id'];

    public function users()
    {
        return $this->belongsTo('App\Models\Users');
    }
}
