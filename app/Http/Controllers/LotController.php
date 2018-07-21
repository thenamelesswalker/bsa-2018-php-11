<?php

namespace App\Http\Controllers;

use App\Entity\Currency;
use App\Entity\Lot;
use App\Http\Requests\LotCreateRequest;
use App\Repository\Contracts\CurrencyRepository;
use App\Request\Implementations\AddLotRequestImpl;
use App\Service\Contracts\MarketService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LotController extends Controller
{
    /** @var MarketService */
    protected $marketService;

    /** @var CurrencyRepository */
    protected $currencyRepository;

    /**
     * LotController constructor.
     * @param MarketService $marketService
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(MarketService $marketService, CurrencyRepository $currencyRepository)
    {
        $this->marketService = $marketService;
        $this->currencyRepository = $currencyRepository;
        $this->middleware('auth');
    }

    public function create()
    {
        if (Gate::allows('createLot')) {
            return view('currency_create');
        }
    }

    public function add(LotCreateRequest $request) {
        if(Gate::denies('createLot')) {
            $message = "Error!";
            return view('currency_add_result', ['message' => "Sorry, error has been occurred: Not authorized"]);
        }

        $currency = $this->currencyRepository->getCurrencyByName($request->currency);
        if(is_null($currency)) {
            return view('currency_add_result', ['message' => "Sorry, error has been occurred: No such currency"]);
        }
        $openDate = Carbon::createFromFormat("Y/m/d H:i:s", $request->openDate)->timestamp;
        $closeDate = Carbon::createFromFormat("Y/m/d H:i:s", $request->closeDate)->timestamp;
        if($openDate > $closeDate) {
            return view('currency_add_result', ['message' => "Sorry, error has been occurred: Dates incorrect"]);
        }

        if($request->price <= 0) {
            return view('currency_add_result', ['message' => "Incorrect price"]);
        }

        $addLotRequest = new AddLotRequestImpl($currency->id, Auth::id(), $openDate, $closeDate, $request->price);
        $this->marketService->addLot($addLotRequest);

        return view('currency_add_result', ['message' => "Lot has been added successfully!"]);
    }
}
