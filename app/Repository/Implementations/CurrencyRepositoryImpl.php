<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 10:40
 */

namespace App\Repository\Implementations;


use App\Entity\Currency;
use App\Repository\Contracts\CurrencyRepository;

class CurrencyRepositoryImpl implements CurrencyRepository
{

    public function add(Currency $currency): Currency
    {
        $currency->save();
        return $currency;
    }

    public function getById(int $id): ?Currency
    {
        return Currency::find($id);
    }

    public function getCurrencyByName(string $name): ?Currency
    {
        return Currency::where('name', $name)->first();
    }

    /**
     * @return Currency[]
     */
    public function findAll()
    {
        return Currency::all();
    }
}