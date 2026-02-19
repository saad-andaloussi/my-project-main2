<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition()
    {
        return [
            'name' => fake()->word() . '-' . fake()->numerify('###'),
            'resource_category_id' => ResourceCategory::query()->inRandomOrder()->first()->id ?? ResourceCategory::factory(),
            'serial_number' => fake()->unique()->bothify('SN-####-????'),
            'cpu_cores' => fake()->randomElement([2, 4, 8, 16, 32, 64]),
            'ram_gb' => fake()->randomElement([16, 32, 64, 128, 256, 512]),
            'storage_gb' => fake()->randomElement([256, 512, 1024, 2048, 4096, 10240]),
            'purchase_price' => fake()->randomFloat(2, 1000, 100000),
            'status' => 'available',
            'price_per_hour' => fake()->randomFloat(2, 10, 500),
            'description' => fake()->sentence(),
            'location' => fake()->randomElement(['Rack A', 'Rack B', 'Rack C', 'DC-1', 'DC-2']),
            'bandwidth_gbps' => fake()->randomElement([1, 10, 25, 100]),
            'storage_type' => fake()->randomElement(['HDD', 'SSD', 'NVMe', 'Hybrid']),
        ];
    }

    /**
     * Indicate that the resource should be available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
        ]);
    }

    /**
     * Indicate that the resource should be in use.
     */
    public function inUse(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_use',
        ]);
    }

    /**
     * Indicate that the resource should be in maintenance.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}

