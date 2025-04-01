<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CryptoService
{
    protected $apiBase = "https://api.coingecko.com/api/v3/";

    public function getCryptoData($symbol)
    {
        try {
            $symbol = strtolower($symbol);

            $response = Http::get($this->apiBase . "coins/markets", [
                'vs_currency' => 'usd',
                'ids' => $symbol,
                'order' => 'market_cap_desc',
                'per_page' => 1,
                'page' => 1,
                'sparkline' => true,
                'price_change_percentage' => '24h'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("API Error: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("HTTP Request Failed: " . $e->getMessage());
            return [];
        }
    }
    public function getAllCoins()
    {
        try {
            $response = Http::get($this->apiBase . "coins/list");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("API Error: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("HTTP Request Failed: " . $e->getMessage());
            return [];
        }
    }

    public function getCoinsByMarketCap($perPage = 50, $page = 1)
{
    try {
        $response = Http::get($this->apiBase . "coins/markets", [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc', // Sort by market cap (highest to lowest)
            'per_page' => $perPage,      // Number of results per page
            'page' => $page,             // Page number for pagination
            'sparkline' => false         // Disable sparkline data
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("API Error: " . $response->body());
        return [];
    } catch (\Exception $e) {
        Log::error("HTTP Request Failed: " . $e->getMessage());
        return [];
    }
}

    
}
