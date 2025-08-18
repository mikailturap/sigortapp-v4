<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kimlik Doğrulama Varsayılanları
    |--------------------------------------------------------------------------
    |
    | Bu seçenek, uygulamanız için varsayılan kimlik doğrulama "guard"ını ve şifre
    | sıfırlama "broker"ını tanımlar. Bu değerleri gerektiği gibi değiştirebilirsiniz,
    | ancak çoğu uygulama için mükemmel bir başlangıçtır.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Kimlik Doğrulama Guard'ları
    |--------------------------------------------------------------------------
    |
    | Sonra, uygulamanız için her kimlik doğrulama guard'ını tanımlayabilirsiniz.
    | Tabii ki, oturum depolama artı Eloquent kullanıcı sağlayıcısını kullanan
    | harika bir varsayılan yapılandırma sizin için tanımlanmıştır.
    |
    | Tüm kimlik doğrulama guard'larının, kullanıcıların veritabanınızdan veya
    | uygulama tarafından kullanılan diğer depolama sisteminden nasıl gerçekte
    | alındığını tanımlayan bir kullanıcı sağlayıcısı vardır. Tipik olarak Eloquent kullanılır.
    |
    | Desteklenen: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kullanıcı Sağlayıcıları
    |--------------------------------------------------------------------------
    |
    | Tüm kimlik doğrulama guard'larının, kullanıcıların veritabanınızdan veya
    | uygulama tarafından kullanılan diğer depolama sisteminden nasıl gerçekte
    | alındığını tanımlayan bir kullanıcı sağlayıcısı vardır. Tipik olarak Eloquent kullanılır.
    |
    | Birden fazla kullanıcı tablosu veya modeliniz varsa, modeli / tabloyu
    | temsil etmek için birden fazla sağlayıcı yapılandırabilirsiniz. Bu sağlayıcılar
    | daha sonra tanımladığınız ekstra kimlik doğrulama guard'larına atanabilir.
    |
    | Desteklenen: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Şifreleri Sıfırlama
    |--------------------------------------------------------------------------
    |
    | Bu yapılandırma seçenekleri, token depolama için kullanılan tablo ve
    | kullanıcıları gerçekte almak için çağrılan kullanıcı sağlayıcısı dahil
    | olmak üzere Laravel'in şifre sıfırlama işlevselliğinin davranışını belirtir.
    |
    | Geçerlilik süresi, her sıfırlama token'ının geçerli sayılacağı dakika
    | sayısıdır. Bu güvenlik özelliği token'ları kısa ömürlü tutar, böylece
    | tahmin edilmeleri için daha az zamana sahip olurlar. Bunu gerektiği gibi değiştirebilirsiniz.
    |
    | Throttle ayarı, kullanıcının daha fazla şifre sıfırlama token'ı
    | oluşturmadan önce beklemesi gereken saniye sayısıdır. Bu, kullanıcının
    | çok büyük miktarda şifre sıfırlama token'ı hızlıca oluşturmasını önler.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Şifre Onaylama Zaman Aşımı
    |--------------------------------------------------------------------------
    |
    | Burada, şifre onaylama penceresinin süresi dolduğunda ve kullanıcıların
    | onay ekranından tekrar şifre girmesini istediğiniz saniye sayısını tanımlayabilirsiniz.
    | Varsayılan olarak, zaman aşımı üç saat sürer.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
