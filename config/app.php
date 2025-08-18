<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Uygulama Adı
    |--------------------------------------------------------------------------
    |
    | Bu değer, çerçeve bir bildirimde veya uygulama adının
    | görüntülenmesi gereken diğer UI öğelerinde uygulamanızın adını yerleştirmesi gerektiğinde kullanılacak uygulamanızın adıdır.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama Ortamı
    |--------------------------------------------------------------------------
    |
    | Bu değer, uygulamanızın şu anda çalıştığı "ortamı" belirler.
    | Bu, uygulamanın kullandığı çeşitli servisleri nasıl
    | yapılandırmayı tercih ettiğinizi belirleyebilir. Bunu ".env" dosyanızda ayarlayın.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama Hata Ayıklama Modu
    |--------------------------------------------------------------------------
    |
    | Uygulamanız hata ayıklama modundayken, uygulama içinde
    | oluşan her hatada detaylı hata mesajları ve yığın izleri
    | gösterilecektir. Devre dışı bırakılırsa, basit genel bir hata sayfası gösterilir.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Uygulama URL'si
    |--------------------------------------------------------------------------
    |
    | Bu URL, Artisan komut satırı aracını kullanırken URL'leri
    | düzgün şekilde oluşturmak için konsol tarafından kullanılır.
    | Bunu uygulamanın köküne ayarlamalısınız, böylece
    | Artisan komutları içinde kullanılabilir.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama Zaman Dilimi
    |--------------------------------------------------------------------------
    |
    | Burada uygulamanız için varsayılan zaman dilimini belirtebilirsiniz,
    | bu PHP tarih ve tarih-saat fonksiyonları tarafından kullanılacaktır.
    | Zaman dilimi varsayılan olarak "UTC" olarak ayarlanmıştır,
    | çünkü çoğu kullanım durumu için uygundur.
    |
    */

    'timezone' => 'Europe/Istanbul',

    /*
    |--------------------------------------------------------------------------
    | Uygulama Yerel Ayar Yapılandırması
    |--------------------------------------------------------------------------
    |
    | Uygulama yerel ayarı, Laravel'in çeviri / yerelleştirme
    | yöntemleri tarafından kullanılacak varsayılan yerel ayarı belirler.
    | Bu seçenek, çeviri planladığınız herhangi bir yerel ayar için
    | ayarlanabilir.
    |
    */

    'locale' => env('APP_LOCALE', 'tr'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'tr'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Şifreleme Anahtarı
    |--------------------------------------------------------------------------
    |
    | Bu anahtar Laravel'in şifreleme servisleri tarafından kullanılır ve
    | tüm şifrelenmiş değerlerin güvenli olmasını sağlamak için rastgele,
    | 32 karakterlik bir dize olarak ayarlanmalıdır. Bunu uygulamayı
    | dağıtmadan önce yapmalısınız.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bakım Modu Sürücüsü
    |--------------------------------------------------------------------------
    |
    | Bu yapılandırma seçenekleri, Laravel'in "bakım modu" durumunu belirlemek
    | ve yönetmek için kullanılan sürücüyü belirler. "cache" sürücüsü,
    | bakım modunun çoklu makine üzerinde kontrol edilmesini sağlayacaktır.
    |
    | Desteklenen sürücüler: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
