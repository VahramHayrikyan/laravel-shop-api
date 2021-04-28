<?php
return [
    'cacheExpiryTime' => env('CACHE_EXPIRY_TIME', '900'), //Cache expiry Time in second
    "access-key" => env('ACCESS_KEY', ''),
    "s3_bucket_url" => env('S3_BUCKET_URL'),
    "s3_thumb_url" => env('S3_THUMB_URL'),
    "microservices" => [
        "product-mgmt" => [
            'url' => env('PRODUCT_MGMT_URL'),
            'key' => env('PRODUCT_MGMT_KEY')
        ],
        "user-mgmt" => [
            'url' => env('USER_MGMT_URL'),
            'key' => env('USER_MGMT_KEY')
        ],
        "order-mgmt" => [
            'url' => env('ORDER_MGMT_URL'),
            'key' => env('ORDER_MGMT_KEY')
        ],
        "integrations" => [
            'url' => env('INTEGRATIONS_URL'),
            'key' => env('INTEGRATIONS_KEY')
        ],
    ],
    "front-url" => [
        'platform' => env('PLATFORM_URL'),
        'admin' => env('ADMIN_URL')
    ]
];
