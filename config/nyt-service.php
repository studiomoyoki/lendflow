<?php

return [
    'nyt' => [
        'api-url' => env('NYT_API_URL', 'https://api.nytimes.com/svc/books/v3/'),
        'app-id' => env('NYT_APP_ID'),
        'key' => env('NYT_KEY'),
        'secret' => env('NYT_SECRET'),
        'best-sellers' => 'lists/best-sellers/history.json',
    ],
];
