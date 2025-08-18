<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Oturum Sürücüsü
    |--------------------------------------------------------------------------
    |
    | Bu seçenek, gelen istekler için kullanılan varsayılan oturum
    | sürücüsünü belirler. Laravel, oturum verilerini kalıcı hale
    | getirmek için çeşitli depolama seçeneklerini destekler.
    | Veritabanı depolama harika bir varsayılan seçimdir.
    |
    | Desteklenen: "file", "cookie", "database", "memcached",
    |            "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Yaşam Süresi
    |--------------------------------------------------------------------------
    |
    | Burada, oturumun süresi dolmadan önce boşta kalmasına izin
    | verilecek dakika sayısını belirtebilirsiniz. Tarayıcı
    | kapatıldığında hemen süresi dolmasını istiyorsanız,
    | expire_on_close yapılandırma seçeneği ile bunu belirtebilirsiniz.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Oturum Şifreleme
    |--------------------------------------------------------------------------
    |
    | Bu seçenek, tüm oturum verilerinizin saklanmadan önce
    | şifrelenmesi gerektiğini kolayca belirtmenizi sağlar.
    | Tüm şifreleme Laravel tarafından otomatik olarak
    | gerçekleştirilir ve oturumu normal gibi kullanabilirsiniz.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Oturum Dosya Konumu
    |--------------------------------------------------------------------------
    |
    | "file" oturum sürücüsü kullanırken, oturum dosyaları diske
    | yerleştirilir. Varsayılan depolama konumu burada tanımlanmıştır;
    | ancak, bunların saklanması gereken başka bir konum
    | sağlamakta özgürsünüz.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Veritabanı Bağlantısı
    |--------------------------------------------------------------------------
    |
    | "database" veya "redis" oturum sürücülerini kullanırken, bu
    | oturumları yönetmek için kullanılması gereken bir bağlantı
    | belirtebilirsiniz. Bu, veritabanı yapılandırma seçeneklerinizdeki
    | bir bağlantıya karşılık gelmelidir.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Veritabanı Tablosu
    |--------------------------------------------------------------------------
    |
    | "database" oturum sürücüsünü kullanırken, oturumları saklamak
    | için kullanılacak tabloyu belirtebilirsiniz. Tabii ki, sizin
    | için mantıklı bir varsayılan tanımlanmıştır; ancak, bunu
    | başka bir tabloya değiştirmekte özgürsünüz.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Önbellek Deposu
    |--------------------------------------------------------------------------
    |
    | Çerçevenin önbellek sürücülü oturum arka uçlarından birini
    | kullanırken, istekler arasında oturum verilerini saklamak
    | için kullanılması gereken önbellek deposunu tanımlayabilirsiniz.
    | Bu, tanımladığınız önbellek depolarından biriyle eşleşmelidir.
    |
    | Etkiler: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Sweep Lottery
    |--------------------------------------------------------------------------
    |
    | Bazı oturum sürücüleri, eski oturumları depolama konumundan
    | temizlemek için manuel olarak geçiş yapmalıdır. İşte,
    | bir istekte bu oluşma olasılığı. Varsayılan olarak,
    | olasılık 2'dir.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Oturum Çerez Adı
    |--------------------------------------------------------------------------
    |
    | Burada, çerçeve tarafından oluşturulan oturum çerezini
    | değiştirebilirsiniz. Genellikle, bu değeri değiştirmenize
    | gerek yoktur, çünkü bu, anlamlı bir güvenlik iyileştirmesi
    | sağlamaz.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::snake((string) env('APP_NAME', 'laravel')).'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Oturum Çerez Yolu
    |--------------------------------------------------------------------------
    |
    | Oturum çerez yolu, çerezin kullanılabilir olduğu yolu belirler.
    | Genellikle, uygulamanızın kök yolu olmalıdır, ancak
    | gerekirse bunu değiştirebilirsiniz.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Çerez Alanı
    |--------------------------------------------------------------------------
    |
    | Bu değer, çerezin kullanılabilir olduğu etki alanını ve alt
    | etki alanlarını belirler. Varsayılan olarak, çerez
    | kök etki alanına ve tüm alt etki alanlarına kullanılabilir.
    | Genellikle, bu değeri değiştirmenize gerek yoktur.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Sadece Çerezler
    |--------------------------------------------------------------------------
    |
    | Bu seçeneği true olarak ayarlayarak, çerezlerin sadece HTTPS
    | bağlantısı olan tarayıcılardan sunucuya geri gönderilmesini
    | sağlayabilirsiniz. Bu, çerezlerin güvenli olmadığında
    | sunucuya gönderilmesini engeller.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Erişim Sadece
    |--------------------------------------------------------------------------
    |
    | Bu değeri true olarak ayarlayarak, çerezin değerine
    | JavaScript'in erişmesini engelleyebilirsiniz ve çerez
    | sadece HTTP protokolü üzerinden erişilebilir olacaktır.
    | Bu seçeneği devre dışı bırakmamanız gerekir.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Aynı Site Çerezleri
    |--------------------------------------------------------------------------
    |
    | Bu seçenek, çapraz site istekleri sırasında çerezlerin
    | davranışını belirler ve CSRF saldırılarını önlemek için
    | kullanılabilir. Varsayılan olarak, "lax" olarak ayarlanır
    | güvenli çapraz site isteklerine izin vermek için.
    |
    | https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Desteklenen: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Bölünmüş Çerezler
    |--------------------------------------------------------------------------
    |
    | Bu değeri true olarak ayarlayarak, çerezin çapraz site
    | bağlamında kök siteye bağlanmasını sağlayabilirsiniz.
    | Bölünmüş çerezler, tarayıcı tarafından "güvenli" olarak
    | işaretlendiğinde ve Same-Site özelliği "none" olarak ayarlandığında
    | kabul edilir.
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
