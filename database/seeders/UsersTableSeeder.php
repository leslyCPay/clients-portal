<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lesly A. Barua',
            'email' => 'lbarua@claimpay.net',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => null,
        ]);
    }
}
