<?php
/**
 * Created by PhpStorm.
 * User: MzCosty
 */

namespace App\Repositories;


use App\Mission;
use App\Task;
use App\TaskGroup;
use App\Repositories\XpRepository as Xp;
use Illuminate\Container\EntryNotFoundException;

class MissionRepository
{

    /**
     * Mission constructor.
     */
    public function __construct()
    {
    }
    
    public function missionForUser($missionId)
    {
        $mission = Mission::with('user', 'userXp')->find($missionId);
        if(!$mission)
            throw new EntryNotFoundException('Mission not found', 404);

        $tasks = Task::with('taskGroup','userTask')->where('mission_id', $missionId)->get()->keyBy('id');

        $taskGroups = TaskGroup::where('mission_id', $missionId)->get()->keyBy('id');

        return [
            'mission' => $mission->prepareForJson(),
            'tasks' => $tasks,
            'task_groups' => $taskGroups
        ];
    }

    public function completeTask($missionId, $taskId)
    {
        $xp = new Xp($missionId);
        $xp->completeTask($taskId);

        return [
            'id' => $taskId,
            'amount' => $xp->getAmount(),
            'unlockIn' => $xp->getLastTask()->getUnlockedIn()
        ];
    }
}