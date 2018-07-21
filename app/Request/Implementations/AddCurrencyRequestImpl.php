<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:22
 */

namespace App\Request\Implementations;


use App\Request\Contracts\AddCurrencyRequest;

class AddCurrencyRequestImpl implements AddCurrencyRequest
{

    protected $name;

    /**
     * AddCurrencyRequestImpl constructor.
     * @param $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function getName(): string
    {
        return $this->name;
    }
}