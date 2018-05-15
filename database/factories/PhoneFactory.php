<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Phone::class, function (Faker $faker) {
    return [
        'number' => $faker->e164PhoneNumber,
        'label' => $faker->name,
    ];
});
