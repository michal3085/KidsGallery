<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ModeratorAction extends Model
{
    use HasFactory;

    public function user()
    {
        $this->belongsTo('App\Models\User');
    }

    public function newModeratorAction($case, $case_id, $action, $type, $mod_only, $data = NULL)
    {
        if ($case == 'comment') {
            $case = Comment::where('id', $case_id)->first();
            $data = $case->comment;
        }

        $action = new ModeratorAction();
        $action->moderator_id = Auth::id();
        $action->user_id = $case->user_id;
        $action->action = $action;
        $action->data = $data;
        $action->open = 1;
        $action->type = $type;
        $action->type_id = $case_id;
        $action->user_viewed = 0;
        $action->moderator_viewed = 1;
        $action->moderator_only = $mod_only;
        $action->save();

        return 1;
    }
}
