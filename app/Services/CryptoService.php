<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class CryptoService
{

    protected $apiBase = "https://api.coingecko.com/api/v3/";

    public function getCryptoData($symbol)
    {
        $symbol  = strtolower($symbol);

        $response = Http::get($this->apiBase . "coins/markets", [
            'vs_currency' => 'usd',
            'ids' => $symbol,
            'order' => 'market_cap_desc',
            'per_page' => 1,
            'page' => 1,
            'sparkline' => true,
            'price_change_percentage' => '24h'
        ]);
    }
    
}