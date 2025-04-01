<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    // Get all cryptocurrencies
    public function index()
    {
        $cryptos = Crypto::all();
        return response()->json($cryptos);
    }

    // Get details of a specific cryptocurrency
    public function show($symbol)
    {
        $crypto = Crypto::where('symbol', strtoupper($symbol))->first();
        if (!$crypto) {
            return response()->json(['error' => 'Crypto not found'], 404);
        }
        return response()->json($crypto);
    }

    public function allCoins()
{
    $coins = $this->cryptoService->getAllCoins();

    if (empty($coins)) {
        return response()->json(['error' => 'Failed to fetch cryptocurrency list'], 500);
    }

    return response()->json($coins);
}

    // Fetch and update cryptocurrency data
    public function fetch(Request $request)
    {
        $symbol = $request->input('symbol');

        $data = $this->cryptoService->getCryptoData($symbol);

        if (empty($data)) {
            return response()->json(['error' => 'Crypto not found'], 404);
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
               // 'chart' => json_encode($coin['sparkline_in_7d']['price'])
            ]
        );

        return response()->json($crypto, 201);
    }

    // Search cryptocurrencies
    public function search(Request $request)
    {
        $query = $request->input('query');

        $cryptos = Crypto::where('name', 'like', "%$query%")
                         ->orWhere('symbol', 'like', "%$query%")
                         ->get();

        return response()->json($cryptos);
    }

    // Historical price chart data
    public function chart($symbol)
    {
        $crypto = Crypto::where('symbol', strtoupper($symbol))->first();

        if (!$crypto || !$crypto->chart) {
            return response()->json(['error' => 'Chart data not available'], 404);
        }

        return response()->json(json_decode($crypto->chart, true));
    }

    public function topCoins(Request $request)
{
    $perPage = $request->input('per_page', 50); // Default to 50 coins per page
    $page = $request->input('page', 1);        // Default to page 1

    $coins = $this->cryptoService->getCoinsByMarketCap($perPage, $page);

    if (empty($coins)) {
        return response()->json(['error' => 'Failed to fetch cryptocurrency data'], 500);
    }

    return response()->json($coins);
}
}