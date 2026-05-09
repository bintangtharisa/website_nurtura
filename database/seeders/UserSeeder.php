<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create Admin User
        User::create([
            'username' => 'admin_user',
            'email' => 'admin@nurtura.com',
            'password_hash' => Hash::make('password123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Father User
        User::create([
            'username' => 'father_user',
            'email' => 'father@nurtura.com',
            'password_hash' => Hash::make('password123'),
            'role' => 'father',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Mother User
        User::create([
            'username' => 'mother_user',
            'email' => 'mother@nurtura.com',
            'password_hash' => Hash::make('password123'),
            'role' => 'mother',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@nurtura.com / password123');
        $this->command->info('Father: father@nurtura.com / password123');
        $this->command->info('Mother: mother@nurtura.com / password123');
    }
}
