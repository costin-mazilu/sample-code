<?php

namespace App;

use CoenJacobs\EloquentCompositePrimaryKeys\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class UserMission extends Model
{
    use HasCompositePrimaryKey;
    
    protected $fillable = array('level', 'progress', 'xp', 'startXp', 'endXp', 'completed_hide', 'completed_last', 'tasks');

    public $timestamps = false;

    protected $primaryKey = ['user_id', 'mission_id'];
    public $incrementing = false;
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function mission()
    {
        return $this->belongsTo('App\Mission');
    }

    public function prepareForJson()
    {
        return [
            'level' => $this->level,
            'progress' => $this->progress,
        ];
    }
    public function OrderForJson()
    {
        return [
            'completed_hide' => (bool) $this->completed_hide,
            'completed_last' => (bool) $this->completed_last,
            'tasks' => $this->tasks
        ];
    }
}
