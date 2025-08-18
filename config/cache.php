<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Önbellek Deposu
    |--------------------------------------------------------------------------
    |
    | Bu seçenek, çerçeve tarafından kullanılacak varsayılan önbellek
    | deposunu kontrol eder. Bu bağlantı, uygulama içinde bir önbellek
    | işlemi çalıştırırken başka bir bağlantı açıkça belirtilmediğinde kullanılır.
    |
    */

    'default' => env('CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Önbellek Deposları
    |--------------------------------------------------------------------------
    |
    | Burada uygulamanız için tüm önbellek "depolarını" ve sürücülerini
    | tanımlayabilirsiniz. Aynı önbellek sürücüsü için birden fazla
    | depo tanımlayarak önbelleklerinizde saklanan öğe türlerini
    | gruplandırabilirsiniz.
    |
    | Desteklenen sürücüler: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Önbellek Anahtarı Öneki
    |--------------------------------------------------------------------------
    |
    | APC, veritabanı, memcached, Redis ve DynamoDB önbellekleri kullanırken
    | aynı önbelleği kullanan diğer uygulamalar olabilir. Bu yüzden
    | çarpışmaları önlemek için her önbellek anahtarına öneki ekleyebilirsiniz.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

];
