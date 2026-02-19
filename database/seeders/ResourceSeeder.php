<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\ResourceCategory;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ResourceCategory::all();

        // Physical Servers
        $serverCategory = $categories->where('slug', 'serveurs-physiques')->first();
        Resource::factory(5)->create([
            'resource_category_id' => $serverCategory->id,
            'cpu_cores' => 16,
            'ram_gb' => 128,
            'storage_gb' => 2000,
            'price_per_hour' => 50,
            'status' => 'available',
        ]);

        // Virtual Machines
        $vmCategory = $categories->where('slug', 'machines-virtuelles')->first();
        Resource::factory(10)->create([
            'resource_category_id' => $vmCategory->id,
            'cpu_cores' => 8,
            'ram_gb' => 32,
            'storage_gb' => 500,
            'price_per_hour' => 25,
            'status' => 'available',
        ]);

        // Storage
        $storageCategory = $categories->where('slug', 'stockage')->first();
        Resource::factory(3)->create([
            'resource_category_id' => $storageCategory->id,
            'storage_gb' => 50000,
            'price_per_hour' => 100,
            'status' => 'available',
        ]);

        // Network Equipment
        $networkCategory = $categories->where('slug', 'equipements-reseau')->first();
        Resource::factory(4)->create([
            'resource_category_id' => $networkCategory->id,
            'bandwidth_gbps' => 10,
            'price_per_hour' => 30,
            'status' => 'available',
        ]);

        // Storage Arrays
        $arrayCategory = $categories->where('slug', 'baies-de-stockage')->first();
        Resource::factory(2)->create([
            'resource_category_id' => $arrayCategory->id,
            'storage_gb' => 100000,
            'storage_type' => 'SSD',
            'price_per_hour' => 200,
            'status' => 'available',
        ]);

        // Software Licenses
        $licenseCategory = $categories->where('slug', 'licenses-logicielles')->first();
        Resource::factory(8)->create([
            'resource_category_id' => $licenseCategory->id,
            'price_per_hour' => 5,
            'status' => 'available',
        ]);

        // Bandwidth
        $bandwidthCategory = $categories->where('slug', 'bande-passante')->first();
        Resource::factory(3)->create([
            'resource_category_id' => $bandwidthCategory->id,
            'bandwidth_gbps' => 100,
            'price_per_hour' => 75,
            'status' => 'available',
        ]);
    }
}
