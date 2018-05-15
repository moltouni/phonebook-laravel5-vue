<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Entities\Contact::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'favorite' => false,
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});


$factory->state(App\Entities\Contact::class, 'favorite', [
    'favorite' => true
]);