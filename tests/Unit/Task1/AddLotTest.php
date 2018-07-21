<?php


namespace Tests\Unit\Task1;


use App\Entity\Lot;
use App\Exceptions\MarketException\ActiveLotExistsException;
use App\Exceptions\MarketException\IncorrectPriceException;
use App\Exceptions\MarketException\IncorrectTimeCloseException;
use App\Repository\Contracts\CurrencyRepository;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\MoneyRepository;
use App\Repository\Contracts\TradeRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\WalletRepository;
use App\Request\Implementations\AddLotRequestImpl;
use App\Service\Contracts\MarketService;
use App\Service\Contracts\WalletService;
use App\Service\Implementations\MarketServiceImpl;
use Carbon\Carbon;
use Tests\TestCase;

class AddLotTest extends TestCase
{
    /** @var MarketService */
    protected $marketService;
    protected $lotRepository;


    protected function setUp()
    {
        parent::setUp();
        $this->lotRepository = $this->createMock(LotRepository::class);
        $this->marketService = new MarketServiceImpl($this->lotRepository,
            $this->app->make(CurrencyRepository::class),
            $this->app->make(MoneyRepository::class),
            $this->app->make(TradeRepository::class),
            $this->app->make(UserRepository::class),
            $this->app->make(WalletRepository::class),
            $this->app->make(WalletService::class)
            );

    }

    public function test_active_lot_exists_exception() {
        $lot = factory(Lot::class)->make();

        $this->lotRepository->method('findActiveLot')->willReturn($lot);

        $request = new AddLotRequestImpl($lot->currency_id, $lot->seller_id, $lot->date_time_open, $lot->date_time_close, $lot->price);

        $this->expectException(ActiveLotExistsException::class);
        $this->marketService->addLot($request);
    }

    public function test_incorrect_time_close_exception() {
        $lot = factory(Lot::class)->make(['date_time_open' => Carbon::now()->subHour()->timestamp,
            'date_time_close' =>  Carbon::now()->subHour(3)->timestamp]);

        $this->lotRepository->method('findActiveLot')->willReturn(null);

        $request = new AddLotRequestImpl($lot->currency_id, $lot->seller_id, $lot->date_time_open, $lot->date_time_close, $lot->price);

        $this->expectException(IncorrectTimeCloseException::class);
        $this->marketService->addLot($request);
    }

    public function test_incorrect_price_exception() {
        $lot = factory(Lot::class)->make(['price' => -1.0]);

        $this->lotRepository->method('findActiveLot')->willReturn(null);

        $request = new AddLotRequestImpl($lot->currency_id, $lot->seller_id, $lot->date_time_open, $lot->date_time_close, $lot->price);

        $this->expectException(IncorrectPriceException::class);
        $this->marketService->addLot($request);
    }

    public function test_add_lot() {
        $lot = factory(Lot::class)->make();

        $this->lotRepository->method('findActiveLot')->willReturn(null);
        $this->lotRepository->method('add')->willReturn($lot);

        $request = new AddLotRequestImpl($lot->currency_id, $lot->seller_id, $lot->date_time_open, $lot->date_time_close, $lot->price);

        $this->assertEquals($lot, $this->marketService->addLot($request));
    }

}