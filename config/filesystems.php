<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Dosya Sistemi Diski
    |--------------------------------------------------------------------------
    |
    | Burada çerçeve tarafından kullanılması gereken varsayılan dosya sistemi
    | diskini belirtebilirsiniz. "local" diski ve çeşitli bulut tabanlı
    | diskler, dosya depolama için uygulamanızda mevcuttur.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Dosya Sistemi Diskleri
    |--------------------------------------------------------------------------
    |
    | Aşağıda gerekli olduğu kadar dosya sistemi diski yapılandırabilir ve
    | aynı sürücü için birden fazla disk yapılandırabilirsiniz. En çok
    | desteklenen depolama sürücüleri için örnekler referans için burada yapılandırılmıştır.
    |
    | Desteklenen sürücüler: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Sembolik Bağlantılar
    |--------------------------------------------------------------------------
    |
    | Burada `storage:link` Artisan komutu çalıştırıldığında oluşturulacak
    | sembolik bağlantıları yapılandırabilirsiniz. Dizi anahtarları
    | bağlantıların konumları olmalı ve değerler hedefleri olmalıdır.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
