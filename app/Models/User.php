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

    public function moderatorActions()
    {
        $this->hasMany('App\Models\ModeratorAction');
    }

    // who blocks that user
    public function blocks()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocks_user');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite');
    }

    public function myFavorites()
    {
        return Favorite::where('user_id', Auth::id())->all();
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

    public function received()
    {
        return Message::where('to_id', Auth::id())->get();
    }

    public function newMessages()
    {
        return Message::where('to_id', Auth::id())->where('read', 0)->get();
    }

    public function countNewMessagesFrom($from)
    {
        return Message::where('to_id', Auth::id())->where('from', $from)->where('read', 0)->count();
    }

    public function conversationExist($to)
    {
        return Conversation::where([
            [ 'user_a', '=', Auth::user()->name ],
            [ 'user_b', '=', $to ]
        ])->orWhere([
            [ 'user_a', '=', $to ],
            [ 'user_b', '=', Auth::user()->name ]
        ])->first();
    }

    public function Conversations()
    {
        return Conversation::where('user_a', Auth::user()->name)
            ->orWhere('user_b', Auth::user()->name)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
    }

    public function unwantedConversation()
    {
        return UnwantedConversation::where('user_id', Auth::id())->get();
    }

    public function roles()
    {
        return $this->hasMany('App\Models\Role');
    }

    public function shouldIWriteToHim($name):int
    {
        $user = User::where('name', $name)->first();

        if ( UsersData::where('user_id', $user->id)->pluck('unfollowing_msg')->first() == 0 ) {
           if ( Follower::where('user_id', $user->id)->where('follow_id', Auth::id())->count() == 1 ) {
               return 1;
           } else {
               return 0;
           }
        } else {
            return 1;
        }
    }

    public function countModeratorActions()
    {
        return ModeratorAction::where('moderator_id', Auth::id())->where('open', 1)->count();
    }

    public function countClosedModeratorActions()
    {
        return ModeratorAction::where('moderator_id', Auth::id())->where('open', 0)->count();
    }
}
