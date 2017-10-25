<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

            DB::table('stores')->insert([ //,
                'user_id' => $faker->numberBetween($min = 1, $max = 1),
                'name_as_url' => $faker->imageUrl($width = 640, $height = 480),
                'name' => $faker->name,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
    }
}
