<?php


namespace App\Repository\Implementations;


use App\Entity\Lot;
use App\Repository\Contracts\LotRepository;
use Carbon\Carbon;

class LotRepositoryImpl implements LotRepository
{

    public function add(Lot $lot): Lot
    {
        $lot->save();
        return $lot;
    }

    public function getById(int $id): ?Lot
    {
        return Lot::find($id);
    }

    /**
     * @return Lot[]
     */
    public function findAll()
    {
        return Lot::all();
    }

    public function findActiveLot(int $userId): ?Lot
    {
        return Lot::where('seller_id', $userId)->where('date_time_close', '>', Carbon::now())->where('date_time_open', '<=', Carbon::now())->first();
    }
}