<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:23
 */

namespace App\Request\Implementations;


use App\Request\Contracts\AddLotRequest;
use App\User;

class AddLotRequestImpl implements AddLotRequest
{

    protected $currencyId;
    protected $sellerId;
    protected $dateTimeOpen;
    protected $dateTimeClose;
    protected $price;

    /**
     * AddLotRequestImpl constructor.
     * @param int $currencyId
     * @param int $sellerId
     * @param int $dateTimeOpen
     * @param int $dateTimeClose
     * @param float $price
     */
    public function __construct(int $currencyId, int $sellerId, int $dateTimeOpen, int $dateTimeClose, float $price)
    {
        $this->currencyId = $currencyId;
        $this->sellerId = $sellerId;
        $this->dateTimeOpen = $dateTimeOpen;
        $this->dateTimeClose = $dateTimeClose;
        $this->price = $price;
    }


    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    /**
     * An identifier of user
     *
     * @return int
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * Timestamp
     *
     * @return int
     */
    public function getDateTimeOpen(): int
    {
        return $this->dateTimeOpen;
    }

    /**
     * Timestamp
     *
     * @return int
     */
    public function getDateTimeClose(): int
    {
        return $this->dateTimeClose;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}