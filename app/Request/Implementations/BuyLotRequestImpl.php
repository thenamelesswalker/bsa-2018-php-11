<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:23
 */

namespace App\Request\Implementations;


use App\Request\Contracts\BuyLotRequest;

class BuyLotRequestImpl implements BuyLotRequest
{

    protected $userId;
    protected $lotId;
    protected $amount;

    /**
     * BuyLotRequestImpl constructor.
     * @param $userId
     * @param $lotId
     * @param $amount
     */
    public function __construct(int $userId, int $lotId, int $amount)
    {
        $this->userId = $userId;
        $this->lotId = $lotId;
        $this->amount = $amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLotId(): int
    {
        return $this->lotId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}