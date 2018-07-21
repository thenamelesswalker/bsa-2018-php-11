<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:08
 */

namespace App\Repository\Implementations;


use App\Entity\Trade;
use App\Repository\Contracts\TradeRepository;

class TradeRepositoryImpl implements TradeRepository
{

    public function add(Trade $trade): Trade
    {
        $trade->save();
        return $trade;
    }
}