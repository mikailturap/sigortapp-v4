<?php

namespace Database\Seeders;

use App\Models\InsuranceCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insuranceCompanies = [
            [
                'name' => 'Axa Sigorta',
                'description' => 'Fransız kökenli uluslararası sigorta şirketi',
                'sort_order' => 1,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Allianz Sigorta',
                'description' => 'Alman kökenli uluslararası sigorta şirketi',
                'sort_order' => 2,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Gulf Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 3,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Neova Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 4,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Sompo Sigorta',
                'description' => 'Japon kökenli uluslararası sigorta şirketi',
                'sort_order' => 5,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'HDI Sigorta',
                'description' => 'Alman kökenli uluslararası sigorta şirketi',
                'sort_order' => 6,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Mapfre Sigorta',
                'description' => 'İspanyol kökenli uluslararası sigorta şirketi',
                'sort_order' => 7,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Unico Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 8,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Ray Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 9,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Türkiye Sigorta',
                'description' => 'Türk devlet sigorta şirketi',
                'sort_order' => 10,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Anadolu Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 11,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Güneş Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 12,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Koru Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 13,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Quick Sigorta',
                'description' => 'Türk sigorta şirketi',
                'sort_order' => 14,
                'is_active' => true,
                'is_deletable' => false
            ],
            [
                'name' => 'Groupama Sigorta',
                'description' => 'Fransız kökenli uluslararası sigorta şirketi',
                'sort_order' => 15,
                'is_active' => true,
                'is_deletable' => false
            ]
        ];

        foreach ($insuranceCompanies as $company) {
            InsuranceCompany::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
}
