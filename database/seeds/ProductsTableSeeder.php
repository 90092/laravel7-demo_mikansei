<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Products;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Categories::all()->pluck('id');
        DB::table('products')->truncate();
        Products::unguard();
        factory(Products::class, 15)->create()->each(function ($product) use ($categories) {
            $faker = Faker::create();
            $product->categories = json_encode($categories->random($faker->numberBetween(0, 5))->all());
            $product->save();
        });
        Products::reguard();
    }
}
