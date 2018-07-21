<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:24
 */

namespace App\Request\Implementations;


use App\Request\Contracts\CreateWalletRequest;

class CreateWalletRequestImpl implements CreateWalletRequest
{

    protected $userId;

    /**
     * CreateWalletRequestImpl constructor.
     * @param $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}