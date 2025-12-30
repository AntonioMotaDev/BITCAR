<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@bitcar.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Supervisor
        User::create([
            'name' => 'Supervisor Principal',
            'email' => 'supervisor@bitcar.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'status' => 'active',
        ]);

        // Operadores
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@bitcar.com',
            'password' => Hash::make('password'),
            'role' => 'operador',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'María García',
            'email' => 'maria.garcia@bitcar.com',
            'password' => Hash::make('password'),
            'role' => 'operador',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Carlos López',
            'email' => 'carlos.lopez@bitcar.com',
            'password' => Hash::make('password'),
            'role' => 'operador',
            'status' => 'active',
        ]);
    }
}
