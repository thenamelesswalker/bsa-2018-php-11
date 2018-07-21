<?php

namespace App\Entity;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        "id",
        "user_id"
    ];
}
