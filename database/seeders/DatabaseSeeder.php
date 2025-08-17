<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            PolicyTypeSeeder::class, // Poliçe türleri
            InsuranceCompanySeeder::class, // Sigorta şirketleri
            DashboardPrivacySettingSeeder::class,
            FullDataSeeder::class, // Tam dolu 250 veri
            // Eski örnek seed'ler devre dışı
            // CustomerSeeder::class,
            // PolicySeeder::class,
        ]);
    }
}
