<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:09
 */

namespace App\Repository\Implementations;


use App\Repository\Contracts\UserRepository;
use App\User;

class UserRepositoryImpl implements UserRepository
{

    public function getById(int $id): ?User
    {
        return User::find($id);
    }
}