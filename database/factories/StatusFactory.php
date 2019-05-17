<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Status::class, function (Faker $faker) {
    $date = $faker->date.' ' .$faker->time;
    return [
        'content' =>$faker->text(),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
