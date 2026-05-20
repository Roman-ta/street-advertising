<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создай первого админа
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'romanta1670@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'admin',
            'legal_signed' => true,
            'profile_complete' => true,
        ]);
    }
}
