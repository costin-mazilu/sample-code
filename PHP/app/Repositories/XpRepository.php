<?php
/**
 * Created by PhpStorm.
 * User: MzCosty
 */

namespace App\Repositories;


use App\Mission;
use App\Task;
use App\UserTask;
use Exception;
use Illuminate\Support\Facades\Auth;

class XpRepository
{
    const lvlCap = 500;
    /**
     * @var null|\App\UserMission
     */
    private $mission = null;

    private $userXp = null;
    /**
     * @var null | \App\Task
     */
    private $lastTask = null;
    private $amount = 0;

    /**
     * XpRepository constructor.
     * @param null $missionId
     * @throws Exception
     */
    public function __construct($missionId = null)
    {
        if($missionId)
            $this->initMission($missionId);
    }

    public function initMission($missionId)
    {
        $this->mission = Mission::with('userXp')->find($missionId);
        if (!$this->mission)
            throw new Exception('Mission not found', 404);
        if (!$this->mission->userXp)
            throw new Exception('The user is not subscribed to this mission', 500);
        
        $this->setUserXp();
    }

    public function completeTask($taskId)
    {
        $this->lastTask = Task::with('userTask')->find($taskId);
        if (!$this->lastTask)
            throw new Exception('The task was not found', 404);
        if ($this->lastTask->mission_id != $this->mission->id)
            throw new Exception('The task does not belong to the mission', 500);

        if ($this->lastTask->getUnlockedIn() > 0)
            throw new Exception('The task is not yet unlocked', 500);

        $this->addXp($this->lastTask->xp());

        //Add new user task
        $this->lastTask->userTask = new UserTask([
            'user_id' => Auth::user()->id,
            'task_id' => $taskId
        ]);
        
        $this->userXp->save();
        $this->lastTask->userTask->save();
    }
    
    public function setUserXp()
    {
        $this->userXp = $this->mission->userXp;
    }
    
    public function addXp($xp)
    {
        $this->amount = 0;
        $this->userXp->xp += $xp;
        $this->updateXp();
    }

    public function updateXp()
    {
        $this->checkLvlUp();
    }

    private function checkLvlUp()
    {

        if ($this->userXp->xp > $this->userXp->endXp) {
            $this->userXp->startXp = $this->userXp->endXp;
            $this->userXp->endXp += $this->getNextLvlXp();
            $this->userXp->level += 1;

            $this->amount += 100 - $this->userXp->progress;
            $this->userXp->progress = 0;
            $this->checkLvlUp();
        } else {
            $progress = $this->getProgress();
            $this->amount += $progress - $this->userXp->progress;
            $this->userXp->progress = $progress;
        }
    }

    private function getProgress()
    {
        $thisLvlMaxXp = $this->userXp->endXp - $this->userXp->startXp;
        $thisLvlXp = $this->userXp->xp - $this->userXp->startXp;

        $progress = round(($thisLvlXp / $thisLvlMaxXp) * 100);

        return $progress;
    }

    private function getNextLvlXp()
    {
        if ($this->mission->exponential)
            return self::lvlCap + (100 * $this->userXp->level);
        else
            return self::lvlCap;
    }

    /**
     * @return \App\Task|null
     */
    public function getLastTask()
    {
        return $this->lastTask;
    }

    /**
     * @return \App\UserMission|null
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * @return null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return null
     */
    public function getUserXp()
    {
        return $this->userXp;
    }
    
    

    /**
     * @param \App\UserMission|null $mission
     */
    public function setMission($mission)
    {
        $this->mission = $mission;
    }

    /**
     * @param Task|null $lastTask
     */
    public function setLastTask($lastTask)
    {
        $this->lastTask = $lastTask;
    }

}