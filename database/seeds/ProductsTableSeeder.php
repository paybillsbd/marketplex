<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 5;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('products')->insert([ //,
                'user_id' => $faker->numberBetween($min = 1, $max = 1),
                'store_id' => $faker->randomNumber,
                'market_product_id' => $faker->randomNumber,
                'title' => $faker->name,
                'description' => $faker->text,
                'mrp' => $faker->randomNumber(3),
                'discount' => $faker->randomNumber(2),
            ]);
        }
    }
}
