<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:21
 */

namespace App\Response\Implementations;


use App\Response\Contracts\LotResponse;

class LotResponseImpl implements LotResponse
{


    protected $id;
    protected $userName;
    protected $currencyName;
    protected $amount;
    protected $dateTimeOpen;
    protected $dateTimeClose;
    protected $price;

    /**
     * LotResponseImpl constructor.
     * @param $id
     * @param $userName
     * @param $currencyName
     * @param $amount
     * @param $dateTimeOpen
     * @param $dateTimeClose
     * @param $price
     */
    public function __construct(int $id, string $userName, string $currencyName, float $amount, string $dateTimeOpen, string $dateTimeClose, string $price)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->currencyName = $currencyName;
        $this->amount = $amount;
        $this->dateTimeOpen = $dateTimeOpen;
        $this->dateTimeClose = $dateTimeClose;
        $this->price = $price;
    }


    /**
     * An identifier of lot
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getCurrencyName(): string
    {
        return $this->currencyName;
    }

    /**
     * All amount of currency that user has in the wallet.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Format: yyyy/mm/dd hh:mm:ss
     *
     * @return string
     */
    public function getDateTimeOpen(): string
    {
        return $this->dateTimeOpen;
    }

    /**
     * Format: yyyy/mm/dd hh:mm:ss
     *
     * @return string
     */
    public function getDateTimeClose(): string
    {
        return $this->dateTimeClose;
    }

    /**
     * Price per one amount of currency.
     *
     * Format: 00,00
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }
}