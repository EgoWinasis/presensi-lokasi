<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'superadmin',
                'foto' => null,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'foto' => null,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'role' => 'user',
                'foto' => null,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create(array_merge($user, [
                'theme_mode' => 'light',
                'timezone' => 'Asia/Jakarta',
                'language' => 'id',
                'notification_email' => true,
                'notification_web' => true,
            ]));
        }
    }
}
