<?php

/*
|--------------------------------------------------------------------------
| Test Durumu
|--------------------------------------------------------------------------
|
| Test fonksiyonlarınız için sağladığınız closure her zaman belirli bir PHPUnit test
| durumu sınıfına bağlıdır. Varsayılan olarak, bu sınıf "PHPUnit\Framework\TestCase"dir.
| Tabii ki, farklı sınıflar veya trait'ler bağlamak için "pest()" fonksiyonunu
| kullanarak bunu değiştirmeniz gerekebilir.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Beklentiler
|--------------------------------------------------------------------------
|
| Test yazarken, değerlerin belirli koşulları karşıladığını kontrol etmeniz
| gerekir. "expect()" fonksiyonu, farklı şeyleri doğrulamak için
| kullanabileceğiniz bir dizi "beklenti" yöntemine erişim sağlar.
| Tabii ki, Beklenti API'sini istediğiniz zaman genişletebilirsiniz.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Fonksiyonlar
|--------------------------------------------------------------------------
|
| Pest çok güçlü olmasına rağmen, her dosyada tekrarlamak istemediğiniz
| projeye özel bazı test kodlarınız olabilir. Burada yardımcıları
| global fonksiyonlar olarak da açığa çıkarabilirsiniz, böylece test
| dosyalarınızdaki kod satırı sayısını azaltmaya yardımcı olur.
|
*/

function something()
{
    // ..
}
