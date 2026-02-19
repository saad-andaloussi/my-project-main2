<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;

class ResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Serveurs Physiques',
                'slug' => 'serveurs-physiques',
                'description' => 'Serveurs dédiés et équipements matériels robustes.',
            ],
            [
                'name' => 'Machines Virtuelles',
                'slug' => 'machines-virtuelles',
                'description' => 'Environnements virtualisés pour la flexibilité.',
            ],
            [
                'name' => 'Stockage',
                'slug' => 'stockage',
                'description' => 'Solutions de stockage SAN, NAS et cloud.',
            ],
            [
                'name' => 'Équipements Réseau',
                'slug' => 'equipements-reseau',
                'description' => 'Commutateurs, routeurs et équipements de connectivité.',
            ],
            [
                'name' => 'Baies de Stockage',
                'slug' => 'baies-de-stockage',
                'description' => 'Baies de stockage haute capacité.',
            ],
            [
                'name' => 'Licenses Logicielles',
                'slug' => 'licenses-logicielles',
                'description' => 'Licenses et accès aux outils logiciels.',
            ],
            [
                'name' => 'Bande Passante',
                'slug' => 'bande-passante',
                'description' => 'Capacités de connectivité Internet et intranet.',
            ],
        ];

        foreach ($categories as $category) {
            ResourceCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
