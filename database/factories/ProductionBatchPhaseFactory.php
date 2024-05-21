<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductionOrderPhase;

class ProductionBatchPhaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionOrderPhase::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_batch_id' => $this->faker->numberBetween(-10000, 10000),
            'phase_id' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
