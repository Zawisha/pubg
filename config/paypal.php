<?php
return [
    'client' => env('PAYPAL_CLIENT_ID', 'Af_wHfbSZfWe7RH4X8L0M0ToyZroI9LJM7RX9LfgKpS-9qBSM7jh-uwqPnijfm5vmhleEHlGb9Ky4jZM'),
    'secret' => env('PAYPAL_SECRET', 'ECSV9ZUJkl5mP2MD_Vx6_l9MGlb9RUKZUl5mKiFSIDET2RNV81rPzGDpR7PFihrLaL6r8ylVyWjKdjd4'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];