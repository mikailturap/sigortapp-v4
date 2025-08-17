<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'customer_title' => 'Ahmet Yılmaz',
                'customer_identity_number' => '12345678901',
                'phone' => '0532 123 45 67',
                'email' => 'ahmet.yilmaz@email.com',
                'address' => 'Atatürk Mah. Cumhuriyet Cad. No:123 İstanbul',
                'customer_type' => 'bireysel',
                'status' => 'aktif',
                'notes' => 'Güvenilir müşteri, düzenli ödemeler'
            ],
            [
                'customer_title' => 'ABC Şirketi A.Ş.',
                'customer_identity_number' => '1234567890',
                'phone' => '0212 345 67 89',
                'email' => 'info@abc.com.tr',
                'address' => 'Levent Mah. Büyükdere Cad. No:456 İstanbul',
                'customer_type' => 'kurumsal',
                'status' => 'aktif',
                'notes' => 'Kurumsal müşteri, yıllık sözleşme'
            ],
            [
                'customer_title' => 'Fatma Demir',
                'customer_identity_number' => '98765432109',
                'phone' => '0533 987 65 43',
                'email' => 'fatma.demir@email.com',
                'address' => 'Kızılay Mah. İstiklal Cad. No:789 Ankara',
                'customer_type' => 'bireysel',
                'status' => 'aktif',
                'notes' => 'Yeni müşteri, ilk poliçe'
            ],
            [
                'customer_title' => 'XYZ Ticaret Ltd. Şti.',
                'customer_identity_number' => '9876543210',
                'phone' => '0232 456 78 90',
                'email' => 'info@xyzticaret.com',
                'address' => 'Alsancak Mah. Kıbrıs Şehitleri Cad. No:321 İzmir',
                'customer_type' => 'kurumsal',
                'status' => 'aktif',
                'notes' => 'Riskli müşteri, sıkı takip gerekli'
            ],
            [
                'customer_title' => 'Mehmet Kaya',
                'customer_identity_number' => '11122233344',
                'phone' => '0535 111 22 33',
                'email' => 'mehmet.kaya@email.com',
                'address' => 'Çankaya Mah. Atatürk Bulvarı No:555 Ankara',
                'customer_type' => 'bireysel',
                'status' => 'aktif',
                'notes' => 'Orta risk, düzenli takip'
            ],
            [
                'customer_title' => 'DEF İnşaat San. Tic. A.Ş.',
                'customer_identity_number' => '5556667778',
                'phone' => '0224 555 66 77',
                'email' => 'info@definsaat.com',
                'address' => 'Nilüfer Mah. FSM Bulvarı No:888 Bursa',
                'customer_type' => 'kurumsal',
                'status' => 'aktif',
                'notes' => 'Kritik risk, günlük takip gerekli'
            ],
            [
                'customer_title' => 'Ayşe Özkan',
                'customer_identity_number' => '99988877766',
                'phone' => '0536 999 88 77',
                'email' => 'ayse.ozkan@email.com',
                'address' => 'Karşıyaka Mah. Atatürk Cad. No:999 Antalya',
                'customer_type' => 'bireysel',
                'status' => 'aktif',
                'notes' => 'Güvenilir müşteri'
            ],
            [
                'customer_title' => 'GHI Teknoloji A.Ş.',
                'customer_identity_number' => '4443332221',
                'phone' => '0312 444 33 22',
                'email' => 'info@ghiteknoloji.com',
                'address' => 'Çankaya Mah. Kızılay Cad. No:111 Ankara',
                'customer_type' => 'kurumsal',
                'status' => 'aktif',
                'notes' => 'Teknoloji şirketi, güvenilir'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        $this->command->info('8 örnek müşteri oluşturuldu.');
    }
}
