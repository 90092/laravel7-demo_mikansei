<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Categories;
use Faker\Generator as Faker;

$factory->define(categories::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
