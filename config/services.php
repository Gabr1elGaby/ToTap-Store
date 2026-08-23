<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'vip_reseller' => [
        'api_id' => env('NEW_API_ID', 'UEsJ21pX'),
        'api_key' => env('NEW_API_KEY', 'wTpFb8UKOona2Hm56HODEruuB7F2aAE0MQU2dXgjjRy1Q2lCUUfL7Un9mcgxtLRy'),
        'base_url' => env('VIP_RESELLER_BASE_URL', 'https://vip-reseller.co.id/api'),
    ],

    'midtrans' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'M720440362'),
        'client_key' => env('MIDTRANS_CLIENT_KEY', 'Mid-client-j5_lQIPsu4FpDtlk'),
        'server_key' => env('MIDTRANS_SERVER_KEY', 'Mid-server-ckZHwiXrG6K0f-NXv3ykujHi'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    ],

];
