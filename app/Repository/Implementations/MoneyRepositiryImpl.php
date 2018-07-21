<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:05
 */

namespace App\Repository\Implementations;


use App\Entity\Money;
use App\Repository\Contracts\MoneyRepository;

class MoneyRepositiryImpl implements MoneyRepository
{

    public function save(Money $money): Money
    {
        $money->save();
        return $money;
    }

    public function findByWalletAndCurrency(int $walletId, int $currencyId): ?Money
    {
        return Money::where('wallet_id', $walletId)->where('currency_id', $currencyId)->first();
    }
}