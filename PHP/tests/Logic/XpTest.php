<?php
/**
 * Created by PhpStorm.
 * User: Costin Mazilu
 */

namespace Tests\Logic;


use App\Mission;
use App\Repositories\XpRepository;
use App\Task;
use App\UserMission;
use Tests\TestCase;

class XpTest extends TestCase
{
    public function testRepoXp()
    {
        $repo = new XpRepository();
        $mission = new Mission([
            'exponential' => 1,
            'userXp' => new UserMission([
                'level' => 1,
                'progress' => 0,
                'xp' => 0,
                'startXp' => 0,
                'endXp' => 500
            ])
        ]);
        $repo->setMission($mission);
        $repo->setUserXp();


        //Xp adding without Lvl Up
        $repo->addXp(250);
        $this->assertEquals(250, $repo->getUserXp()->xp);
        $this->assertEquals(50, $repo->getUserXp()->progress);
        $this->assertEquals(50, $repo->getAmount());

        //Test Lvl Up 150 remaining Xp
        $repo->addXp(400);
        $this->assertEquals(650, $repo->getUserXp()->xp);
        $this->assertEquals(2, $repo->getUserXp()->level);
        $this->assertEquals(500, $repo->getUserXp()->startXp);
        $this->assertEquals(1100, $repo->getUserXp()->endXp);
        $this->assertEquals(25, $repo->getUserXp()->progress);
        $this->assertEquals(75, $repo->getAmount());

        //Test Double Lvl Up 400 remaining Xp
        $repo->addXp(1550);
        $this->assertEquals(2200, $repo->getUserXp()->xp);
        $this->assertEquals(4, $repo->getUserXp()->level);
        $this->assertEquals(1800, $repo->getUserXp()->startXp);
        $this->assertEquals(2600, $repo->getUserXp()->endXp);
        $this->assertEquals(50, $repo->getUserXp()->progress);
        $this->assertEquals(225, $repo->getAmount());//75+100+50

        //Test progress that is not a round number
        $repo->addXp(50);
        $this->assertEquals(56, $repo->getUserXp()->progress);
        $this->assertEquals(6, $repo->getAmount());//75+100+50
    }

    public function testTaskXp()
    {
        $task = new Task([
            'type' => 0,
            'importance' => 5,
            'difficulty' => 5
        ]);

        //Task xp default
        $this->assertEquals(100, $task->xp());

        //Task type variation
        $task->type = 1;
        $this->assertEquals(125, $task->xp());
        $task->type = 2;
        $this->assertEquals(300, $task->xp());
        $task->type = 3;
        $this->assertEquals(600, $task->xp());
        $task->type = 4;
        $this->assertEquals(1100, $task->xp());
        $task->type = 0;
        //Task xp importance variation
        $task->importance = 8;
        $this->assertEquals(115, $task->xp());
        $task->importance = 2;
        $this->assertEquals(85, $task->xp());

        //Task xp difficulty variation
        $task->importance = 5;
        $task->difficulty = 8;
        $this->assertEquals(115, $task->xp());
        $task->difficulty = 2;
        $this->assertEquals(85, $task->xp());
    }
}