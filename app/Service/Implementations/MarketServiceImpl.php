<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:14
 */

namespace App\Service\Implementations;


use App\Entity\Lot;
use App\Entity\Trade;
use App\Exceptions\MarketException\ActiveLotExistsException;
use App\Exceptions\MarketException\BuyInactiveLotException;
use App\Exceptions\MarketException\BuyNegativeAmountException;
use App\Exceptions\MarketException\BuyOwnCurrencyException;
use App\Exceptions\MarketException\IncorrectLotAmountException;
use App\Exceptions\MarketException\IncorrectPriceException;
use App\Exceptions\MarketException\IncorrectTimeCloseException;
use App\Exceptions\MarketException\LotDoesNotExistException;
use App\Exceptions\WalletException\WalletProcessingBuyException;
use App\Mail\TradeCreated;
use App\Repository\Contracts\CurrencyRepository;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\MoneyRepository;
use App\Repository\Contracts\TradeRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\WalletRepository;
use App\Request\Contracts\AddLotRequest;
use App\Request\Contracts\BuyLotRequest;
use App\Request\Implementations\MoneyRequestImpl;
use App\Response\Contracts\LotResponse;
use App\Response\Implementations\LotResponseImpl;
use App\Service\Contracts\WalletService;
use App\Service\Contracts\MarketService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MarketServiceImpl implements MarketService
{

    /** @var LotRepository */
    protected $lotRepository;

    /** @var CurrencyRepository */
    protected $currencyRepository;

    /** @var MoneyRepository */
    protected $moneyRepository;

    /** @var TradeRepository */
    protected $tradeRepository;

    /** @var UserRepository */
    protected $userRepository;

    /** @var WalletRepository */
    protected $walletRepository;

    /** @var WalletService */
    protected $walletService;

    /**
     * MarketServiceImpl constructor.
     * @param LotRepository $lotRepository
     * @param CurrencyRepository $currencyRepository
     * @param MoneyRepository $moneyRepository
     * @param TradeRepository $tradeRepository
     * @param UserRepository $userRepository
     * @param WalletRepository $walletRepository
     * @param WalletService $walletService
     */
    public function __construct(LotRepository $lotRepository, CurrencyRepository $currencyRepository, MoneyRepository $moneyRepository, TradeRepository $tradeRepository, UserRepository $userRepository, WalletRepository $walletRepository, WalletService $walletService)
    {
        $this->lotRepository = $lotRepository;
        $this->currencyRepository = $currencyRepository;
        $this->moneyRepository = $moneyRepository;
        $this->tradeRepository = $tradeRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->walletService = $walletService;
    }

    /**
     * Sell currency.
     *
     * @param AddLotRequest $lotRequest
     *
     * @throws ActiveLotExistsException
     * @throws IncorrectTimeCloseException
     * @throws IncorrectPriceException
     *
     * @return Lot
     * @throws \Throwable
     */
    public function addLot(AddLotRequest $lotRequest): Lot
    {
        $lot = $this->lotRepository->findActiveLot($lotRequest->getSellerId());
        throw_if(!is_null($lot), new ActiveLotExistsException());
        throw_if($lotRequest->getDateTimeClose() < $lotRequest->getDateTimeOpen(), new IncorrectTimeCloseException());
        throw_if($lotRequest->getPrice() < 0, new IncorrectPriceException());
        $lot = new Lot();
        $lot->currency_id = $lotRequest->getCurrencyId();
        $lot->seller_id = $lotRequest->getSellerId();
        $lot->price = $lotRequest->getPrice();
        $lot->date_time_open = Carbon::createFromTimestamp($lotRequest->getDateTimeOpen());
        $lot->date_time_close = Carbon::createFromTimestamp($lotRequest->getDateTimeClose());
        $lot = $this->lotRepository->add($lot);
        return $lot;
    }

    /**
     * Buy currency.
     *
     * @param BuyLotRequest $lotRequest
     *
     * @throws BuyOwnCurrencyException
     * @throws IncorrectLotAmountException
     * @throws BuyNegativeAmountException
     * @throws BuyInactiveLotException
     *
     * @return Trade
     * @throws \Throwable
     */
    public function buyLot(BuyLotRequest $lotRequest): Trade
    {
        $lot = $this->lotRepository->getById($lotRequest->getLotId());
        throw_if($lotRequest->getUserId() == $lot->seller_id, new BuyOwnCurrencyException());
        throw_if($lotRequest->getAmount() < 0, new BuyNegativeAmountException());
        throw_if($lotRequest->getAmount() > $lot->amount || $lotRequest->getAmount() < 1, new IncorrectLotAmountException());
        throw_if($lot->date_time_close < Carbon::now(), new BuyInactiveLotException());

        $sellerUser = $this->userRepository->getById($lot->seller_id);
        $buyerUser = $this->userRepository->getById($lotRequest->getUserId());

        $sellerWallet = $this->walletRepository->findByUser($sellerUser->id);
        $buyerWallet = $this->walletRepository->findByUser($buyerUser->id);

        DB::beginTransaction();
        try {
            $this->walletService->addMoney(new MoneyRequestImpl($buyerWallet->id, $lot->currency_id, $lotRequest->getAmount()));
            $this->walletService->takeMoney(new MoneyRequestImpl($sellerWallet->id, $lot->currency_id, $lotRequest->getAmount()));

            $trade = new Trade();
            $trade->lot_id = $lot->id;
            $trade->user_id = $buyerUser->id;
            $trade->amount = $lotRequest->getAmount();
            $trade = $this->tradeRepository->add($trade);
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw new WalletProcessingBuyException();
        }
        DB::commit();
        Mail::to($sellerUser->email)->cc($buyerUser->email)->send(new TradeCreated($trade));
        return $trade;
    }


    /**
     * Retrieves lot by an identifier and returns it in LotResponse format
     *
     * @param int $id
     *
     * @throws LotDoesNotExistException
     *
     * @return LotResponse
     * @throws \Throwable
     */
    public function getLot(int $id): LotResponse
    {
        $lot = $this->lotRepository->getById($id);
        throw_if(is_null($lot), new LotDoesNotExistException());
        $currency = $this->currencyRepository->getById($lot->currency_id);
        $user = $this->userRepository->getById($lot->seller_id);
        $openDate = Carbon::createFromTimestamp($lot->getDateTimeOpen())->format('Y/m/d H:i:s');
        $closeDate = Carbon::createFromTimestamp($lot->getDateTimeClose())->format('Y/m/d H:i:s');
        $wallet = $this->walletRepository->findByUser($lot->seller_id);
        $money = $this->moneyRepository->findByWalletAndCurrency($wallet->id, $currency->id);
        return new LotResponseImpl($lot->id, $user->name, $currency->name, $money->amount, $openDate, $closeDate, $lot->price);
    }

    /**
     * Return list of lots.
     *
     * @return LotResponse[]
     */
    public function getLotList(): array
    {
        $lots = $this->lotRepository->findAll();
        $result = [];
        foreach ($lots as $lot) {
            $result[] = $this->getLot($lot->id);
        }
        return $result;
    }
}