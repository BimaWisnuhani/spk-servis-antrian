<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\User::create([
        'name' => 'Admin Bengkel',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('revive899'),
    ]);
}
}
