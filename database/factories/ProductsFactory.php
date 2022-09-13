<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Products;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    $price = rand(1, 500);
    $discount = 0;

    if (rand(0, 1)) {
        $discount = rand(1, $price - 1);
    }

    return [
        'name' => $faker->word,
        'price' => $price,
        'discount' => $discount,
        'stock' => rand(0, 10),
        'detail' => $faker->text(),
    ];
});
