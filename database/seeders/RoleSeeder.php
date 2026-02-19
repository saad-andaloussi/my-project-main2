<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(
            ['name' => 'guest'],
            ['label' => 'Invité', 'description' => 'Peut consulter les ressources en lecture seule']
        );
        
        Role::firstOrCreate(
            ['name' => 'user'],
            ['label' => 'Utilisateur Interne', 'description' => 'Peut faire des réservations et signaler des incidents']
        );
        
        Role::firstOrCreate(
            ['name' => 'manager'],
            ['label' => 'Responsable Technique', 'description' => 'Gère les ressources et approuve les réservations']
        );
        
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrateur', 'description' => 'Accès complet à l\'application']
        );
    }
}
