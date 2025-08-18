<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Veritabanı seed'lerini çalıştır.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Murat Akiz',
            'email' => 'muratgbb@hotmail.com',
            'password' => Hash::make('murat6336!'),
        ]);
    }
}
