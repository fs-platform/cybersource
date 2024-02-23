<?php

return [
    'environment' => env('CYBERSOURCE_ENVIRONMENT','sandbox'),

    'channel'     =>  env('CYBERSOURCE_CHANNEL','cybersource'),

    'sandbox' => [
        'usd' => [
            'point' => env('CYBERSOURCE_SANDBOX_USD_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_SANDBOX_USD_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_SANDBOX_USD_KEY', ''),
            'secret' => env('CYBERSOURCE_SANDBOX_USD_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_SANDBOX_USD_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_SANDBOX_USD_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_SANDBOX_USD_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_SANDBOX_USD_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_SANDBOX_USD_ALLOWED_CARD_NETWORKS', []),
        ],
        'mxn' => [
            'point' => env('CYBERSOURCE_SANDBOX_MXN_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_SANDBOX_MXN_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_SANDBOX_MXN_KEY', ''),
            'secret' => env('CYBERSOURCE_SANDBOX_MXN_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_SANDBOX_MXN_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_SANDBOX_MXN_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_SANDBOX_MXN_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_SANDBOX_MXN_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_SANDBOX_MXN_ALLOWED_CARD_NETWORKS', []),
        ],
        'cad' => [
            'point' => env('CYBERSOURCE_SANDBOX_CAD_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_SANDBOX_CAD_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_SANDBOX_CAD_KEY', ''),
            'secret' => env('CYBERSOURCE_SANDBOX_CAD_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_SANDBOX_CAD_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_SANDBOX_CAD_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_SANDBOX_CAD_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_SANDBOX_CAD_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_SANDBOX_CAD_ALLOWED_CARD_NETWORKS', []),
        ]
    ],

    'production' => [
        'usd' => [
            'point' => env('CYBERSOURCE_PRODUCTION_USD_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_PRODUCTION_USD_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_PRODUCTION_USD_KEY', ''),
            'secret' => env('CYBERSOURCE_PRODUCTION_USD_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_PRODUCTION_USD_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_PRODUCTION_USD_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_PRODUCTION_USD_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_PRODUCTION_USD_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_PRODUCTION_USD_ALLOWED_CARD_NETWORKS', []),
        ],
        'mxn' => [
            'point' => env('CYBERSOURCE_PRODUCTION_MXN_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_PRODUCTION_MXN_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_PRODUCTION_MXN_KEY', ''),
            'secret' => env('CYBERSOURCE_PRODUCTION_MXN_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_PRODUCTION_MXN_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_PRODUCTION_MXN_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_PRODUCTION_MXN_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_PRODUCTION_MXN_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_PRODUCTION_MXN_ALLOWED_CARD_NETWORKS', []),
        ],
        'cad' => [
            'point' => env('CYBERSOURCE_PRODUCTION_CAD_POINT', ''),
            'merchant_id' => env('CYBERSOURCE_PRODUCTION_CAD_MERCHANT_ID', ''),
            'key' => env('CYBERSOURCE_PRODUCTION_CAD_KEY', ''),
            'secret' => env('CYBERSOURCE_PRODUCTION_CAD_SECRET', ''),
            'authentication_type' => env('CYBERSOURCE_PRODUCTION_CAD_AUTHENTICATION_TYPE', ''),
            'encryption_type' => env('CYBERSOURCE_PRODUCTION_CAD_ENCRYPTION_TYPE', ''),
            'target_origins' => env('CYBERSOURCE_PRODUCTION_CAD_TARGET_ORIGINS', []),
            'client_version' => env('CYBERSOURCE_PRODUCTION_CAD_CLIENT_VERSION', ''),
            'allowed_card_networks' => env('CYBERSOURCE_PRODUCTION_CAD_ALLOWED_CARD_NETWORKS', []),
        ]
    ]
];

