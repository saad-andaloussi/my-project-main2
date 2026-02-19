<?php

namespace Database\Factories;

use App\Models\ResourceCategory;

use Illuminate\Database\Eloquent\Factories\Factory;



class ResourceCategoryFactory extends Factory
{
    protected $model = ResourceCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
