<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InputBatch;

class InputBatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InputBatch::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'input_id' => $this->faker->numberBetween(-10000, 10000),
            'supplier_id' => $this->faker->numberBetween(-10000, 10000),
            'buying_price' => $this->faker->randomFloat(2, 0, 999999.99),
            'selling_price' => $this->faker->randomFloat(2, 0, 999999.99),
            'date_supplied' => $this->faker->dateTime(),
            'quantity_purchased' => $this->faker->numberBetween(-10000, 10000),
            'quantity_remaining' => $this->faker->numberBetween(-10000, 10000),
            'pack_size_id' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
