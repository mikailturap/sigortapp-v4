<?php

namespace Database\Seeders;

use App\Models\PolicyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policyTypes = [
            [
                'name' => 'Zorunlu Trafik Sigortası',
                'description' => 'Motorlu araçlar için zorunlu trafik sigortası',
                'sort_order' => 1,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Kasko',
                'description' => 'Kapsamlı araç sigortası',
                'sort_order' => 2,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'DASK',
                'description' => 'Doğal Afet Sigortası Kurumu',
                'sort_order' => 3,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Konut Sigortası',
                'description' => 'Ev ve işyeri sigortası',
                'sort_order' => 4,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Sağlık Sigortası',
                'description' => 'Sağlık ve hastalık sigortası',
                'sort_order' => 5,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'TARSİM',
                'description' => 'Tarım Sigortaları Havuzu',
                'sort_order' => 6,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'İşyeri Sigortası',
                'description' => 'İşyeri ve işveren sorumluluk sigortası',
                'sort_order' => 7,
                'is_active' => true,
                'is_deletable' => true
            ],
            [
                'name' => 'Seyahat Sigortası',
                'description' => 'Yurt içi ve yurt dışı seyahat sigortası',
                'sort_order' => 8,
                'is_active' => true,
                'is_deletable' => true
            ]
        ];

        foreach ($policyTypes as $policyType) {
            PolicyType::updateOrCreate(
                ['name' => $policyType['name']],
                $policyType
            );
        }
    }
}
