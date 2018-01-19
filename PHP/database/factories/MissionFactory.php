<?php

use Faker\Generator as Faker;

$factory->define(App\Mission::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'public' => false,
        'name' => $faker->word,
        'exponential' => true
    ];
});
