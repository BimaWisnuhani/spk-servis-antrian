<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil UserSeeder agar akun Admin otomatis terbuat
        $this->call(UserSeeder::class);

        // 2. Panggil Seeder SPK & Antrian
        $this->call(BobotKriteriaSeeder::class);
        $this->call(SubKriteriaSeeder::class);
        $this->call(AntrianServisSeeder::class);
    }
}
