<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-21
 * Time: 14:51
 */

namespace Tests\Unit\Task2;


use App\Entity\Currency;
use App\Entity\Lot;
use App\Entity\Money;
use App\Entity\Wallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LotTest extends TestCase
{

    use RefreshDatabase;

    public function test_unathorized_add_lot() {
        $responce = $this->json("POST", '/api/v1/lots', [
                'currency_id' => 1,
                'date_time_open' => Carbon::now()->timestamp,
                'date_time_close' => Carbon::now()->addHour(1)->timestamp,
                'price' => 10,
            ]);
        $responce->assertHeader('Content-Type', 'application/json');
        $responce->assertStatus(403);
    }

    public function test_cant_add_lot() {
        $user = factory(User::class)->create();
        $responce = $this->actingAs($user)
            ->json("POST", '/api/v1/lots', [
                'currency_id' => 1,
                'date_time_open' => Carbon::now()->timestamp,
                'date_time_close' => Carbon::now()->addHour(1)->timestamp,
                'price' => 10,
            ]);
        $responce->assertHeader('Content-Type', 'application/json');
        $responce->assertStatus(400);
    }

    public function test_can_add_lot() {
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->create(['user_id' => $user->id]);
        $currency = factory(Currency::class)->create();
        $money = factory(Money::class)->create(['wallet_id' => $wallet->id, 'currency_id' => $currency->id]);
        $responce = $this->actingAs($user)
            ->json("POST", '/api/v1/lots', [
                'currency_id' => $money->currency_id,
                'date_time_open' => Carbon::now()->timestamp,
                'date_time_close' => Carbon::now()->addHour(1)->timestamp,
                'price' => 1,
            ]);
        $responce->assertHeader('Content-Type', 'application/json');
        $responce->assertStatus(201);
    }

    public function test_unathorized_buy_lot() {
        $responce = $this->json("POST", '/api/v1/trades', [
            'lot_id' => 1,
            'amount' => 10,
        ]);
        $responce->assertHeader('Content-Type', 'application/json');
        $responce->assertStatus(403);
    }

    public function test_cant_buy_lot() {
        $user = factory(User::class)->create();
        $responce = $this->actingAs($user)
            ->json("POST", '/api/v1/trades', [
                'lot_id' => 1,
                'amount' => 10,
            ]);
        $responce->assertHeader('Content-Type', 'application/json');
        $responce->assertStatus(400);
    }

}