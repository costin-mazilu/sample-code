<?php

use Faker\Generator as Faker;

$factory->define(App\TaskGroup::class, function (Faker $faker) {
    return [
        'mission_id' => 0,
        'name' => $faker->word,
        'color' => $faker->hexColor
    ];
});
