<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Kuyruk Bağlantı Adı
    |--------------------------------------------------------------------------
    |
    | Laravel'in kuyruğu, tek, birleşik bir API aracılığıyla çeşitli
    | arka uçları destekler, her arka uç için aynı sözdizimini
    | kullanarak her arka uca uygun erişim sağlar. Varsayılan
    | kuyruk bağlantısı aşağıda tanımlanmıştır.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Kuyruk Bağlantıları
    |--------------------------------------------------------------------------
    |
    | Burada uygulamanız tarafından kullanılan her kuyruk arka ucu
    | için bağlantı seçeneklerini yapılandırabilirsiniz. Laravel
    | tarafından desteklenen her arka uç için örnek bir yapılandırma
    | sağlanmıştır. Daha fazla eklemekte de özgürsünüz.
    |
    | Sürücüler: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            'queue' => env('DB_QUEUE', 'default'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            'block_for' => null,
            'after_commit' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | İş Toplu İşleme
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki seçenekler, iş toplu işleme bilgilerini saklayan
    | veritabanı ve tabloyu yapılandırır. Bu seçenekler, uygulamanız
    | tarafından tanımlanan herhangi bir veritabanı bağlantısı ve
    | tabloya güncellenebilir.
    |
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Başarısız Kuyruk İşleri
    |--------------------------------------------------------------------------
    |
    | Bu seçenekler, başarısız kuyruk işi loglama davranışını
    | yapılandırır, böylece başarısız işlerin nasıl ve nerede
    | saklanacağını kontrol edebilirsiniz. Laravel, başarısız
    | işleri basit bir dosyada veya veritabanında saklama
    | desteği ile birlikte gelir.
    |
    | Desteklenen sürücüler: "database-uuids", "dynamodb", "file", "null"
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'failed_jobs',
    ],

];
