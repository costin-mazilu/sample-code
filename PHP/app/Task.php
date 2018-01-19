<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App
 * @property \App\UserTask $userTask
 */
class Task extends Model
{
    protected $fillable = array('task_group_id', 'name', 'type', 'importance', 'difficulty');

    const TYPE_DEFAULT = 0;
    const TYPE_DAILY = 1;
    const TYPE_WEEKLY = 2;
    const TYPE_MONTHLY = 3;
    const TYPE_YEARLY = 4;

    const BASE_XP = 100;
    const XP_MODIFIER = 5;
    const XP_TYPE_MODIFIER = [
        self::TYPE_DEFAULT => 0,
        self::TYPE_DAILY => 25,
        self::TYPE_WEEKLY => 200,
        self::TYPE_MONTHLY => 500,
        self::TYPE_YEARLY => 1000,
    ];

    public function mission()
    {
        return $this->belongsTo('App\Mission');
    }
    
    public function taskGroup()
    {
        return $this->belongsTo('App\TaskGroup');
    }
    
    public function userTasks()
    {
        return $this->hasMany('App\UserTask');
    }

    public function userTask()
    {
        return $this->hasOne('App\UserTask')->latest();
    }

    public function xp()
    {
        $xp = self::BASE_XP;
        $xp += ($this->importance - self::XP_MODIFIER) * self::XP_MODIFIER;
        $xp += ($this->difficulty - self::XP_MODIFIER) * self::XP_MODIFIER;
        
        $xp += self::XP_TYPE_MODIFIER[$this->type];

        return $xp;
    }

    public function getUnlockedIn()
    {
        $unlockedIn = 0;
        if(isset($this->userTask)) {
            $now = Carbon::now();
            switch ($this->type) {
                case self::TYPE_DAILY:
                    if($this->userTask->created_at->isToday())
                        $unlockedIn = $now->secondsUntilEndOfDay();

                    break;

                case self::TYPE_WEEKLY:
                    if($this->userTask->created_at->isSameAs('W', $now))
                        $unlockedIn = $now->diffInSeconds($this->userTask->created_at->endOfWeek());
                    break;

                case self::TYPE_MONTHLY:
                    if($this->userTask->created_at->isSameMonth($now, true))
                        $unlockedIn = $now->diffInSeconds($this->userTask->created_at->endOfMonth());
                    break;

                case self::TYPE_YEARLY:

                    if($this->userTask->created_at->isSameYear($now))
                        $unlockedIn = $now->diffInSeconds($this->userTask->created_at->endOfYear());
                    break;

                default:
                    $unlockedIn = 0;
            }
        }

        return $unlockedIn;
    }


    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'importance' => $this->importance,
            'difficulty' => $this->difficulty,
            'task_group_id' => (int) $this->task_group_id,
            'completed' => $this->getUnlockedIn()
        ];
    }
    
}
