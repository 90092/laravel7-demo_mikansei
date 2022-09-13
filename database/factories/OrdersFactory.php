<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Orders;
use App\Models\Products;
use App\User;

$factory->define(Orders::class, function () {
    $user = User::all()->random();
    $products = Products::all()->random(rand(1, 5));
    $detail = json_encode($products->map(function ($item) {
        return [
            'pid' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'discount' => $item->discount,
            'amount' => 1,
        ];
    }));

    return [
        'member_id' => $user->id,
        'detail' => $detail,
        'fee' => 30,
        'phone' => $user->phone,
        'address' => $user->address,
        'status' => rand(1, 3)
    ];
});
