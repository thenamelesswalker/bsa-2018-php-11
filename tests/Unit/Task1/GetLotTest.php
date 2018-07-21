<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-21
 * Time: 11:45
 */

namespace Tests\Unit\Task1;


use App\Entity\Lot;
use App\Exceptions\MarketException\LotDoesNotExistException;
use App\Repository\Contracts\CurrencyRepository;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\MoneyRepository;
use App\Repository\Contracts\TradeRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\WalletRepository;
use App\Service\Contracts\MarketService;
use App\Service\Contracts\WalletService;
use App\Service\Implementations\MarketServiceImpl;
use Tests\TestCase;

class GetLotTest extends TestCase
{

    /** @var MarketService */
    protected $marketService;
    protected $lotRepository;
    protected $currencyRepository;
    protected $userRepository;
    protected $walletRepository;
    protected $moneyRepository;


    protected function setUp()
    {
        parent::setUp();
        $this->lotRepository = $this->createMock(LotRepository::class);
        $this->currencyRepository = $this->createMock(CurrencyRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->walletRepository = $this->createMock(WalletRepository::class);
        $this->moneyRepository = $this->createMock(MoneyRepository::class);
        $this->marketService = new MarketServiceImpl($this->lotRepository,
            $this->currencyRepository,
            $this->moneyRepository,
            $this->app->make(TradeRepository::class),
            $this->userRepository,
            $this->walletRepository,
            $this->app->make(WalletService::class)
        );
    }

    public function test_get_non_existent_lot() {
        $this->lotRepository->method('getById')->willReturn(null);

        $this->expectException(LotDoesNotExistException::class);
        $this->marketService->getLot(999);
    }

//    public function test_get_lot() {
//        $lot = factory(Lot::class)->make();
//        $this->lotRepository->method('getById')->willReturn($lot);
//
//    }

}