<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pictures()
    {
        return $this->hasMany('App\Models\Picture');
    }

    public function usersdata()
    {
        return $this->hasOne('App\Models\UsersData');
    }

    public function blocked()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocks_user', 'user_id');
    }

    // who blocks that user
    public function blocks()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocks_user');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'follow_id', 'user_id');
    }

    // users that follow this user
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follow_id');
    }

    // users who follow each other
    public function friends($friend)
    {
        return Follower::where('follow_id', Auth::id())->where('user_id', $friend)->first();
    }

    public function hasRole($role)
    {
        return Role::where('role', $role)->where('user_id', Auth::id())->first();
    }

    public function roles()
    {
        return $this->hasMany('App\Models\Role');
    }
}
