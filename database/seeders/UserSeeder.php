<?php

namespace Database\Seeders;

use App\Models\DietType;
use App\Models\Goal;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleUser         = Role::where('slug', 'user')->first();
        $roleNutritionist = Role::where('slug', 'nutritionist')->first();
        $roleAdmin        = Role::where('slug', 'admin')->first();

        $goalBajar    = Goal::where('slug', 'bajar_peso')->first();
        $dietVeg      = DietType::where('slug', 'vegetariano')->first();
        $dietOmnivoro = DietType::where('slug', 'omnivoro')->first();

        // ── Admin ────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@nutrichef.com'],
            [
                'role_id'  => $roleAdmin->id,
                'name'     => 'Admin NutriChef',
                'password' => Hash::make('password'),
                'active'   => true,
                'email_verified_at' => now(),
            ]
        );

        // ── Nutricionista ────────────────────────────────────────
        $nutricionista = User::firstOrCreate(
            ['email' => 'elena@nutrichef.com'],
            [
                'role_id'  => $roleNutritionist->id,
                'name'     => 'Dra. Elena Suárez',
                'password' => Hash::make('password'),
                'active'   => true,
                'email_verified_at' => now(),
            ]
        );

        // ── Usuario regular ──────────────────────────────────────
        $usuario = User::firstOrCreate(
            ['email' => 'maria@example.com'],
            [
                'role_id'  => $roleUser->id,
                'name'     => 'María González',
                'password' => Hash::make('password'),
                'active'   => true,
                'email_verified_at' => now(),
            ]
        );

        // Perfil del usuario
        UserProfile::firstOrCreate(
            ['user_id' => $usuario->id],
            [
                'goal_id'                => $goalBajar?->id,
                'diet_type_id'           => $dietVeg?->id,
                'allergies'              => ['mani'],
                'preferences'            => [],
                'notifications_enabled'  => true,
            ]
        );

        // Asignar paciente a nutricionista
        \App\Models\NutritionistPatient::firstOrCreate(
            [
                'nutritionist_id' => $nutricionista->id,
                'patient_id'      => $usuario->id,
            ],
            ['active' => true]
        );

        $this->command->info('Usuarios de prueba creados:');
        $this->command->table(
            ['Rol', 'Email', 'Contraseña'],
            [
                ['Admin',         'admin@nutrichef.com', 'password'],
                ['Nutricionista', 'elena@nutrichef.com', 'password'],
                ['Usuario',       'maria@example.com',   'password'],
            ]
        );
    }
}
