<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    public $timestamps = false;

    protected $fillable = ['mission_id', 'name', 'color'];

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color
        ];
    }
}
