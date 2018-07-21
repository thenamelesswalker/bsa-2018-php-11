<?php

namespace App\Providers;

use App\Repository\Contracts\CurrencyRepository;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\MoneyRepository;
use App\Repository\Contracts\TradeRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\WalletRepository;
use App\Repository\Implementations\CurrencyRepositoryImpl;
use App\Repository\Implementations\LotRepositoryImpl;
use App\Repository\Implementations\MoneyRepositiryImpl;
use App\Repository\Implementations\TradeRepositoryImpl;
use App\Repository\Implementations\UserRepositoryImpl;
use App\Repository\Implementations\WalletRepositoryImpl;
use App\Request\Contracts\AddCurrencyRequest;
use App\Request\Contracts\AddLotRequest;
use App\Request\Contracts\BuyLotRequest;
use App\Request\Contracts\CreateWalletRequest;
use App\Request\Contracts\MoneyRequest;
use App\Request\Implementations\AddCurrencyRequestImpl;
use App\Request\Implementations\AddLotRequestImpl;
use App\Request\Implementations\BuyLotRequestImpl;
use App\Request\Implementations\CreateWalletRequestImpl;
use App\Request\Implementations\MoneyRequestImpl;
use App\Response\Contracts\LotResponse;
use App\Response\Implementations\LotResponseImpl;
use App\Service\Contracts\CurrencyService;
use App\Service\Contracts\MarketService;
use App\Service\Contracts\WalletService;
use App\Service\Implementations\CurrencyServiceImpl;
use App\Service\Implementations\MarketServiceImpl;
use App\Service\Implementations\WalletServiceImpl;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurrencyRepository::class, CurrencyRepositoryImpl::class);
        $this->app->singleton(LotRepository::class, LotRepositoryImpl::class);
        $this->app->singleton(MoneyRepository::class, MoneyRepositiryImpl::class);
        $this->app->singleton(TradeRepository::class, TradeRepositoryImpl::class);
        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
        $this->app->singleton(WalletRepository::class, WalletRepositoryImpl::class);

        $this->app->bind(AddCurrencyRequest::class, AddCurrencyRequestImpl::class);
        $this->app->bind(AddLotRequest::class, AddLotRequestImpl::class);
        $this->app->bind(BuyLotRequest::class, BuyLotRequestImpl::class);
        $this->app->bind(CreateWalletRequest::class, CreateWalletRequestImpl::class);
        $this->app->bind(MoneyRequest::class, MoneyRequestImpl::class);

        $this->app->singleton(CurrencyService::class, CurrencyServiceImpl::class);
        $this->app->singleton(WalletService::class, WalletServiceImpl::class);
        $this->app->singleton(MarketService::class, MarketServiceImpl::class);
    }
}
