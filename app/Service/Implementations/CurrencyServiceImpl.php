<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:13
 */

namespace App\Service\Implementations;

use App\Entity\Currency;
use App\Repository\Contracts\CurrencyRepository;
use App\Request\Contracts\AddCurrencyRequest;
use App\Service\Contracts\CurrencyService;

class CurrencyServiceImpl implements CurrencyService
{

    protected $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function addCurrency(AddCurrencyRequest $currencyRequest): Currency
    {
        $currency = $this->currencyRepository->getCurrencyByName($currencyRequest->getName());
        if(!is_null($currency)) {
            return $currency;
        }
        $currency = new Currency();
        $currency->name = $currencyRequest->getName();
        return $this->currencyRepository->add($currency);
    }
}