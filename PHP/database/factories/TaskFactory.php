<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'mission_id' => 0,
        'task_group_id' => rand(1,3),
        'name' => $faker->word,
        'type' => rand(1,5),
        'importance' => rand(1,10),
        'difficulty' => rand(1,10)
    ];
});
