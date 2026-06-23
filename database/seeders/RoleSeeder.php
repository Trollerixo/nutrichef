<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Usuario',       'slug' => 'user'],
            ['name' => 'Nutricionista', 'slug' => 'nutritionist'],
            ['name' => 'Administrador', 'slug' => 'admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
