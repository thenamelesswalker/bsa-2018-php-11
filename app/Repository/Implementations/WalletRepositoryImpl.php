<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:10
 */

namespace App\Repository\Implementations;


use App\Entity\Wallet;
use App\Repository\Contracts\WalletRepository;

class WalletRepositoryImpl implements WalletRepository
{

    public function add(Wallet $wallet): Wallet
    {
        $wallet->save();
        return $wallet;
    }

    public function findByUser(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }
}