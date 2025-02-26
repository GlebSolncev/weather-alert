<?php

return [
    'weather_api' => [
        'base_url' => 'http://api.weatherapi.com',
        'api_key' => env('WEATHERAPI_API_KEY'),
        'timeout' => 5,
    ],

    'open_meteo' => [
        'base_url' => 'https://api.open-meteo.com',
        'timeout' => 5,
    ],
];
