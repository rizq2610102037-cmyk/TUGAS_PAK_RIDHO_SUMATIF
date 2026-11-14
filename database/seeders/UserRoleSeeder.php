<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $users = [
            // 10 Akun Kaprodi Berdasarkan Jurusan di Universitas Pradita
            [
                'name' => 'Kaprodi Architecture',
                'email' => 'kaprodi.architecture@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Architecture',
            ],
            [
                'name' => 'Kaprodi Urban Planning',
                'email' => 'kaprodi.urbanplanning@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Urban Planning',
            ],
            [
                'name' => 'Kaprodi Civil Engineering',
                'email' => 'kaprodi.civilengineering@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Civil Engineering',
            ],
            [
                'name' => 'Kaprodi Interior Design',
                'email' => 'kaprodi.interiordesign@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Interior Design',
            ],
            [
                'name' => 'Kaprodi Visual Communication Design',
                'email' => 'kaprodi.dkv@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Visual Communication Design',
            ],
            [
                'name' => 'Kaprodi Accounting',
                'email' => 'kaprodi.accounting@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Accounting',
            ],
            [
                'name' => 'Kaprodi Management',
                'email' => 'kaprodi.management@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Management',
            ],
            [
                'name' => 'Kaprodi Informatics',
                'email' => 'kaprodi.informatics@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Informatics',
            ],
            [
                'name' => 'Kaprodi Business Information System',
                'email' => 'kaprodi.bis@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Business Information System',
            ],
            [
                'name' => 'Kaprodi Hospitality & Tourism',
                'email' => 'kaprodi.hospitality@pradita.ac.id',
                'password' => $password,
                'role' => 'kaprodi',
                'prodi' => 'Hospitality & Tourism',
            ],

            // Akun Admin Unit & Biro
            [
                'name' => 'Admin SUAR',
                'email' => 'suar@pradita.ac.id',
                'password' => $password,
                'role' => 'admin_suar',
                'prodi' => null,
            ],
            [
                'name' => 'Admin PIJAR',
                'email' => 'pijar@pradita.ac.id',
                'password' => $password,
                'role' => 'admin_pijar',
                'prodi' => null,
            ],
            [
                'name' => 'Admin Kerma',
                'email' => 'kerma@pradita.ac.id',
                'password' => $password,
                'role' => 'admin_kerma',
                'prodi' => null,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}