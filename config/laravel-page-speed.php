<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Laravel Page Speed
    |--------------------------------------------------------------------------
    |
    | Set this field to false to disable the laravel page speed service.
    | You would probably replace that in your local configuration to get a readable output.
    |
    */
    'enable' => env('LARAVEL_PAGE_SPEED_ENABLE', false),

    /*
    |--------------------------------------------------------------------------
    | Skip Routes
    |--------------------------------------------------------------------------
    |
    | Skip Routes paths to exclude.
    | You can use * as wildcard.
    |
    */
    'skip' => [
        'admin/*',
        'admin_order/*',
        'mainproducts/*',
        'mainproduct/*',
        'complain/*',
        'order/*',
        'user/*',
        'assign_user_complain/*',
        'getorder/*',
        'download-excle/*',
        'admin_orders/*',
        'couriers/*',
        'courier/*',
        'cities/*',
        'city/*',
        'zones/*',
        'zone/*',
        'areas/*',
        'area/*',
        'purchases/*',
        'purchase/*',
        'admin_purchase/*',
        'admin_get/*',
        'returns/*',
        'return/*',
        'admin_return/*',
        'stocks/*',
        'stock/*',
        'supplier-payment/*',
        'suppliers/*',
        'supplier/*',
        'paymenttypes/*',
        'paymenttype/*',
        'payments/*',
        'payment/*',
        'wcustomers/*',
        'wcustomer/*',
        'wsales/*',
        'admin_wsale/*',
        'wsalestocks/*',
        '*.xml',
        '*.less',
        '*.pdf',
        '*.doc',
        '*.txt',
        '*.ico',
        '*.rss',
        '*.zip',
        '*.mp3',
        '*.rar',
        '*.exe',
        '*.wmv',
        '*.doc',
        '*.avi',
        '*.ppt',
        '*.mpg',
        '*.mpeg',
        '*.tif',
        '*.wav',
        '*.mov',
        '*.psd',
        '*.ai',
        '*.xls',
        '*.mp4',
        '*.m4a',
        '*.swf',
        '*.dat',
        '*.dmg',
        '*.iso',
        '*.flv',
        '*.m4v',
        '*.torrent'
    ],
];
