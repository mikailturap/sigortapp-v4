<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Üçüncü Taraf Servisleri
    |--------------------------------------------------------------------------
    |
    | Bu dosya, Mailgun, Postmark, AWS ve daha fazlası gibi üçüncü taraf
    | servisler için kimlik bilgilerini saklamak içindir. Bu dosya, bu
    | tür bilgiler için de facto konum sağlar ve paketlerin çeşitli
    | servis kimlik bilgilerini bulmak için geleneksel bir dosyaya
    | sahip olmasına izin verir.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

];
