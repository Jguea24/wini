<?php

return [
    'provider' => env('COCOA_MARKET_PROVIDER', 'investing'),
    'symbol' => env('COCOA_MARKET_SYMBOL', 'US Cocoa'),
    'currency' => env('COCOA_MARKET_CURRENCY', 'USD'),
    'unit' => env('COCOA_MARKET_UNIT', 'tonelada'),
    'cache_ttl' => (int) env('COCOA_MARKET_CACHE_TTL', 3600),
    'api_url' => env('COCOA_MARKET_API_URL'),
    'api_key' => env('COCOA_MARKET_API_KEY'),
    'scraping_url' => env('COCOA_MARKET_SCRAPING_URL', 'https://es.investing.com/commodities/us-cocoa'),
    'user_agent' => env('COCOA_MARKET_USER_AGENT', 'Mozilla/5.0 (compatible; WiniMarketBot/1.0; +https://wini.local)'),
    'timeout' => (int) env('COCOA_MARKET_TIMEOUT', 15),
];
