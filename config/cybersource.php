<?php

return [
    'environment' => env('CYBERSOURCE_ENVIRONMENT','sandbox'),

    'channel'     =>  env('CYBERSOURCE_CHANNEL','cybersource'),

    'sandbox'     => [
        'key'                   => env('CYBERSOURCE_SANDBOX_KEY',''),
        'secret'                => env('CYBERSOURCE_SANDBOX_SECRET',''),
        'merchant_id'           => env('CYBERSOURCE_SANDBOX_MERCHANT_ID',''),
        'point'                 => env('CYBERSOURCE_SANDBOX_POINT',''),
        'authentication_type'   => env('CYBERSOURCE_SANDBOX_AUTHENTICATION_TYPE',''),
        'target_origins'        => env('CYBERSOURCE_SANDBOX_TARGET_ORIGINS',[]),
        'client_version'        => env('CYBERSOURCE_SANDBOX_CLIENT_VERSION',''),
        'allowed_card_networks' => env('CYBERSOURCE_SANDBOX_ALLOWED_CARD_NETWORKS',[]),
        'return_url'            => env('CYBERSOURCE_SANDBOX_RETURN_URL',''),
        'cancel_url'            => env('CYBERSOURCE_SANDBOX_CANCEL_URL',''),
    ],

    'production' => [
        'key'                   => env('CYBERSOURCE_PRODUCTION_KEY',''),
        'secret'                => env('CYBERSOURCE_PRODUCTION_SECRET',''),
        'merchant_id'           => env('CYBERSOURCE_PRODUCTION_MERCHANT_ID',''),
        'point'                 => env('CYBERSOURCE_PRODUCTION_POINT',''),
        'authentication_type'   => env('CYBERSOURCE_PRODUCTION_AUTHENTICATION_TYPE',''),
        'target_origins'        => env('CYBERSOURCE_PRODUCTION_TARGET_ORIGINS',[]),
        'client_version'        => env('CYBERSOURCE_PRODUCTION_CLIENT_VERSION',''),
        'allowed_card_networks' => env('CYBERSOURCE_PRODUCTION_ALLOWED_CARD_NETWORKS',[]),
        'return_url'            => env('CYBERSOURCE_PRODUCTION_RETURN_URL',''),
        'cancel_url'            => env('CYBERSOURCE_PRODUCTION_CANCEL_URL',''),
    ]
];
