<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Compte Admin
        User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Administrateur',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Compte Cashier
        User::firstOrCreate([
            'email' => 'cashier@gmail.com',
        ], [
            'name' => 'Caissier',
            'email' => 'cashier@gmail.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
            'email_verified_at' => now(),
        ]);
    }
}
