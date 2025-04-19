<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        // 2 Admins with unique emails
        User::factory()->count(2)->sequence(
            fn ($sequence) => [
                'email' => 'admin' . ($sequence->index + 1) . '@example.com',
                'role' => 'admin',
            ]
        )->create();

        // 17 Users with unique emails
        User::factory()->count(17)->sequence(
            fn ($sequence) => [
                'email' => 'user' . ($sequence->index + 1) . '@example.com',
                'role' => 'user',
            ]
        )->create();
    }
}
