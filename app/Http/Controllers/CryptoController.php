<?php

namespace App\Http\Controllers;

use App\Models\Crypto;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class CryptoController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function index()
    {
        $cryptos = Crypto::all();
        return view('cryptos.index', compact('cryptos'));
    }

    public function fetchCrypto($symbol)
    {
        $data = $this->cryptoService->getCryptoData($symbol);

        if(empty($data)){
         return redirect()->back()->withErrors('error','Crypto not found');
        }

        $coin = $data[0];

        $crypto = Crypto::updateOrCreate(
            ['symbol' => $coin['symbol']],
            [
                'name' => $coin['name'],
                'price' => $coin['current_price'],
                'market_cap' => $coin['market_cap'],
                'percent_change_24h' => $coin['price_change_percentage_24h'],
                'ath' => $coin['ath'],
                'atl' => $coin['atl'],
                'chart' => json_encode($coin['sparkline_in_7d']['price'])
            ]
        );

        return redirect()->route('crypto.index')->with('success', 'Crypto data updated!');

    }
}
