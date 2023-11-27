<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate Service Access Keys
    |--------------------------------------------------------------------------
    |
    | This section manages the access keys required for accessing the exchange
    | rate service. Each key serves as an identifier for authentication and
    | authorization purposes when interacting with the exchange rate APIs.
    |
    */

    'xrt' => [
        'url' => 'http://api.exchangerate.host',
        'key' => env('XRT_ACCESS_KEY'),
    ],

];
