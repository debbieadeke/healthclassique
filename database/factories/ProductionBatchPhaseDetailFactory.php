<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductionOrderPhaseDetail;

class ProductionBatchPhaseDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionOrderPhaseDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_batch_phase_id' => $this->faker->numberBetween(-10000, 10000),
            'product_id' => $this->faker->numberBetween(-10000, 10000),
            'percentage' => $this->faker->numberBetween(-1000, 1000),
            'weight' => $this->faker->numberBetween(-10000, 10000),
            'pack_size_id' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
