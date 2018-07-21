<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018-07-19
 * Time: 11:20
 */

namespace App\Service\Implementations;


use App\Entity\Money;
use App\Entity\Wallet;
use App\Exceptions\WalletException\IncorrectAmmountException;
use App\Exceptions\WalletException\WalletAlreadyExistsException;
use App\Repository\Contracts\MoneyRepository;
use App\Repository\Contracts\WalletRepository;
use App\Request\Contracts\CreateWalletRequest;
use App\Request\Contracts\MoneyRequest;
use App\Service\Contracts\WalletService;

class WalletServiceImpl implements WalletService
{

    /** @var  WalletRepository */
    protected $walletRepository;

    /** @var MoneyRepository */
    protected $moneyRepository;

    /**
     * WalletServiceImpl constructor.
     * @param WalletRepository $walletRepository
     * @param MoneyRepository $moneyRepository
     */
    public function __construct(WalletRepository $walletRepository, MoneyRepository $moneyRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->moneyRepository = $moneyRepository;
    }

    /**
     * Add wallet to user.
     *
     * @param CreateWalletRequest $walletRequest
     * @return Wallet
     * @throws \Throwable
     */
    public function addWallet(CreateWalletRequest $walletRequest): Wallet
    {
        $wallet = $this->walletRepository->findByUser($walletRequest->getUserId());
        throw_if(!is_null($wallet), new WalletAlreadyExistsException());
        $wallet = new Wallet();
        $wallet->user_id = $walletRequest->getUserId();
        return $this->walletRepository->save($wallet);
    }

    /**
     * Add money to a wallet.
     *
     * @return Money
     * @throws \Throwable
     */
    public function addMoney(MoneyRequest $moneyRequest): Money
    {
        throw_if($moneyRequest->getAmount() < 0, new IncorrectAmmountException());
        $money = $this->moneyRepository->findByWalletAndCurrency($moneyRequest->getWalletId(), $moneyRequest->getCurrencyId());
        if(is_null($money)) {
            $money = new Money();
            $money->currency_id = $moneyRequest->getCurrencyId();
            $money->wallet_id = $moneyRequest->getWalletId();
            $money->amount = 0;
        }
        $money->amount =+ $moneyRequest->getAmount();
        return $this->moneyRepository->save($money);
    }

    /**
     * Take money from a wallet.
     *
     * @param MoneyRequest $currencyRequest
     * @return Money
     * @throws \Throwable
     */
    public function takeMoney(MoneyRequest $moneyRequest): Money
    {
        $money = $this->moneyRepository->findByWalletAndCurrency($moneyRequest->getWalletId(), $moneyRequest->getCurrencyId());
        throw_if(is_null($money) || $money->amount < $moneyRequest->getAmount(), new IncorrectAmmountException());
        $money->amount -= $moneyRequest->getAmount();
        return $this->moneyRepository->save($money);
    }
}