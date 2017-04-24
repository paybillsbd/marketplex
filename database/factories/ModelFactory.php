<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(MarketPlex\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'api_token' => str_random(60),
    ];
});

$factory->define(MarketPlex\Store::class, function (Faker\Generator $faker) {

    $faker->addProvider(new Faker\Provider\en_IN\Person($faker));
    $faker->addProvider(new Faker\Provider\en_IN\Address($faker));
    $faker->addProvider(new Faker\Provider\en_US\Company($faker));

	$storeName = $faker->company;
    $fakeAddress = [
        'mailing-address' => $faker->address,
        'address_flat_house_floor_building' => $faker->address,
        'address_colony_street_locality' => $faker->streetAddress,
        'address_landmark' => $faker->country,
        'address_town_city' => $faker->city,
        'postcode' => $faker->postcode,
        'state' => $faker->state,
    ];
    return [
        'name' => $storeName,
        // 'domain' => 'com',//$faker->tld,
        'address' => MarketPlex\User::encodeAddress($fakeAddress),
        'name_as_url' => strtolower(preg_replace('/[\s.,\']/', '', $storeName)),
        // 'sub_domain' => 'inzaana',
        'store_type' => 'NOT_SURE',
        'description' => $faker->realText($faker->numberBetween(50,100)),
        'status' => 'ON_APPROVAL',
    ];
});