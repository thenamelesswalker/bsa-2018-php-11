<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-21
 * Time: 01:17
 */

namespace Tests;


use App\Entity\Currency;
use App\User;

class TestDataFactory
{
    public static function createUser(): User {
        return factory(User::class)->create();
    }

    public static function createCurrency(): Currency {
        return factory(Currency::class)->create();
    }
}