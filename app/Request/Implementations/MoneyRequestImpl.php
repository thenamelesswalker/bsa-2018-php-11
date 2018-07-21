<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:25
 */

namespace App\Request\Implementations;


use App\Request\Contracts\MoneyRequest;

class MoneyRequestImpl implements MoneyRequest
{

    protected $walletId;
    protected $currencyId;
    protected $amount;

    /**
     * MoneyRequestImpl constructor.
     * @param $walletId
     * @param $currencyId
     * @param $amount
     */
    public function __construct(int $walletId, int $currencyId, float $amount)
    {
        $this->walletId = $walletId;
        $this->currencyId = $currencyId;
        $this->amount = $amount;
    }


    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}