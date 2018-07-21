<?php

namespace App\Http\Controllers\Api;

use App\Request\Contracts\AddLotRequest;
use App\Request\Contracts\BuyLotRequest;
use App\Request\Implementations\AddLotRequestImpl;
use App\Request\Implementations\BuyLotRequestImpl;
use App\Service\Contracts\MarketService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LotController extends Controller
{

    /** @var MarketService */
    protected $marketService;

    /**
     * LotController constructor.
     * @param MarketService $marketService
     */
    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }


    public function index() {
        return response()->json($this->marketService->getLotList());
    }

    public  function show(int $id) {
        try {
            $lot = $this->marketService->getLot($id);
        } catch(\Exception $e) {
            return response()->json(['error' => ['message' => "Lot not found", 'status_code' => 400]], 400);
        }
        return response()->json($lot);
    }

    public function add(Request $request) {
        if(Gate::denies('createLot')) {
            return response()->json(['error' => ['message' => "Not authorized", 'status_code' => 403]], 403);
        }
        $addRequest = new AddLotRequestImpl(
            $request->currency_id,
            Auth::id(),
            $request->date_time_open,
            $request->date_time_close,
            $request->price);
        try {
            $this->marketService->addLot($addRequest);
        } catch (\Exception $e) {
            return response()->json(['error' => ['message' => "Can't add lot", 'status_code' => 400]], 400);
        }
        return response()->json([], 201);
    }

    public function buy(Request $request) {
        if(Gate::denies('buyLot')) {
            return response()->json(['error' => ['message' => "Not authorized", 'status_code' => 403]], 403);
        }
        $buyRequest = new BuyLotRequestImpl(Auth::id(), $request->lot_id, $request->amount);
        try {
            $this->marketService->buyLot($buyRequest);
        } catch (\Exception $e) {
            return response()->json(['error' => ['message' => "Can't buy lot", 'status_code' => 400]], 400);
        }
        return response()->json([], 201);
    }
}
