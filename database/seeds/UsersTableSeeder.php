<?php

use Illuminate\Database\Seeder;
use Faker\Factory as UserFaker;
use Illuminate\Support\Facades\Log;

use MarketPlex\Helpers\ImageManager;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = UserFaker::create();

        /*
         * seed public user
         *
         * */
        $user = factory(MarketPlex\User::class)->create([
            'name' => 'public',
            'password' => bcrypt('#public?' . strtolower(config('app.vendor')) . '$'),
        	'api_token' => ImageManager::PUBLIC_TOKEN,
        ]);
        if($user)
            Log::info('[' . config('app.vendor') . '][Single public user created for testing]');
        else
            Log::error('[' . config('app.vendor') . '][No public user created] -> [Seeding failed]');
    }
}
