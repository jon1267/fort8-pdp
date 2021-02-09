<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'kleo0707@mail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('respamer1985'),
        ]);

        User::create([
            'role' => 'admin',
            'name' => 'test',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);
    }
}
