<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mission extends Model
{
    protected $fillable = array('exponential', 'userXp');
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    public function tasks() {
        return $this->hasMany('App\Task');
    }

    public function userXp()
    {
        $userId = Auth::check() ? Auth::user()->id : 0;
        return $this->hasOne('App\UserMission')->where('user_id', $userId);
    }

    public function prepareForJson()
    {
        return [
            'id' => $this->id,
            'creator' => $this->user->prepareForJson(),
            'public' => (bool) $this->public,
            'name' => $this->name,
            'xp' => $this->userXp->prepareForJson(),
            'order' => $this->userXp->OrderForJson(),
        ];
    }
}
