<?php

use Faker\Generator as Faker;

$factory->define(\App\Entity\Lot::class, function (Faker $faker) {
    return [
        'currency_id' => 1,
        'seller_id' => 1,
        'date_time_open' => \Carbon\Carbon::now()->timestamp,
        'date_time_close' => \Carbon\Carbon::now()->addHours(1)->timestamp,
        'price' => 1.0,
    ];
});
