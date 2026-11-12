<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Kaprodi SI',
                'email' => 'kaprodi@pradita.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'kaprodi',
            ],
            [
                'name' => 'Admin SUAR',
                'email' => 'suar@pradita.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_suar',
            ],
            [
                'name' => 'Admin PIJAR',
                'email' => 'pijar@pradita.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_pijar',
            ],
            [
                'name' => 'Admin Kerma',
                'email' => 'kerma@pradita.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_kerma',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}