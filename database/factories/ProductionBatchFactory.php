<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductionSetting;

class ProductionBatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionSetting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(-10000, 10000),
            'no_of_phases' => $this->faker->numberBetween(-1000, 1000),
        ];
    }
}
