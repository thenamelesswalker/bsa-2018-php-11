<?php

use Faker\Generator as Faker;

$factory->define(\App\Entity\Money::class, function (Faker $faker) {
    return [
        'wallet_id' => 1,
        'currency_id' => 1,
        'amount' => 10,
    ];
});
