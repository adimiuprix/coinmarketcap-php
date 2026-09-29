<?php

declare(strict_types=1);

return [
    'api_key' => env('COINMARKETCAP_API_KEY'),
    
    'base_url' => env('COINMARKETCAP_BASE_URL', 'https://pro-api.coinmarketcap.com'),
    
    'sandbox_url' => env('COINMARKETCAP_SANDBOX_URL', 'https://sandbox-api.coinmarketcap.com'),
    
    'timeout' => (int) env('COINMARKETCAP_TIMEOUT', 30),
    
    'use_sandbox' => env('COINMARKETCAP_USE_SANDBOX', false),
];
