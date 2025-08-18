<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PolicySeeder extends Seeder
{
    /**
     * Veritabanı seed'lerini çalıştır.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        for ($i = 0; $i < 50; $i++) {
            $policyType = $faker->randomElement([
                'Zorunlu Trafik Sigortası',
                'Kasko',
                'DASK',
                'Konut Sigortası',
                'Sağlık Sigortası',
                'TARSİM',
            ]);

            $insuranceCompanies = [
                'Axa Sigorta',
                'Allianz Sigorta',
                'Gulf Sigorta',
                'Neova Sigorta',
                'Sompo Sigorta',
                'HDI Sigorta',
                'Mapfre Sigorta',
                'Unico Sigorta',
                'Ray Sigorta',
                'Türkiye Sigorta',
                'Anadolu Sigorta',
                'Güneş Sigorta',
                'Koru Sigorta',
                'Quick Sigorta',
                'Groupama Sigorta'
            ];

            Policy::create([
                'customer_title' => $faker->company,
                'customer_identity_number' => $faker->unique()->numerify('###########'),
                'customer_phone' => $faker->phoneNumber,
                'customer_birth_date' => $faker->date('Y-m-d', '2000-01-01'),
                'customer_address' => $faker->address,
                'insured_name' => $faker->boolean(70) ? $faker->company : null, // %70 ihtimalle sigorta ettiren farklı
                'insured_phone' => $faker->boolean(70) ? $faker->phoneNumber : null,
                'policy_type' => $policyType,
                'policy_company' => $faker->randomElement($insuranceCompanies),
                'policy_number' => $faker->unique()->bothify('POL-########'),
                'plate_or_other' => $faker->boolean(80) ? $faker->bothify('##???##') : null,
                'issue_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'start_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'end_date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'document_info' => $faker->boolean(50) ? $faker->bothify('DOC-####') : null,
                'tarsim_business_number' => ($policyType == 'TARSİM') ? $faker->numerify('##########') : null,
                'tarsim_animal_number' => ($policyType == 'TARSİM') ? $faker->numerify('##########') : null,
                'status' => $faker->randomElement(['aktif', 'pasif']),
                'notified_at' => null,
                'notification_dismissed_at' => null,
            ]);
        }
    }
}
