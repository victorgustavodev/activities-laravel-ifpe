<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),  // Troque para senha segura!
                'role' => 'admin',
            ]
        );
    }
}
