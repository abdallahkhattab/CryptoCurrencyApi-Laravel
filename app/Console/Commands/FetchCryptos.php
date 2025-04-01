<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCryptos extends Command
{
    protected $signature = 'fetch:cryptos';

    protected $description = 'Fetch and update cryptocurrency data from CoinGecko';
    
    public function handle()
    {
        $symbols = ['bitcoin', 'ethereum', 'ripple']; // Add more symbols as needed
    
        foreach ($symbols as $symbol) {
            $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                'vs_currency' => 'usd',
                'ids' => $symbol,
                'order' => 'market_cap_desc',
                'per_page' => 1,
                'page' => 1,
               // 'sparkline' => true,
                'price_change_percentage' => '24h'
            ]);
    
            if ($response->successful()) {
                $data = $response->json()[0];
                \App\Models\Crypto::updateOrCreate(
                    ['symbol' => $data['symbol']],
                    [
                        'name' => $data['name'],
                        'price' => $data['current_price'],
                        'market_cap' => $data['market_cap'],
                        'percent_change_24h' => $data['price_change_percentage_24h'],
                        'ath' => $data['ath'],
                        'atl' => $data['atl'],
                      //  'chart' => json_encode($data['sparkline_in_7d']['price'])
                    ]
                );
            }
        }
    
        $this->info('Cryptocurrency data fetched and updated successfully.');
    }
}