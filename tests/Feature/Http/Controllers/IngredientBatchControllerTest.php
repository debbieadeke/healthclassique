<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\IngredientBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InputBatchController
 */
class IngredientBatchControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $ingredientBatches = IngredientBatch::factory()->count(3)->create();

        $response = $this->get(route('ingredient-batch.index'));

        $response->assertOk();
        $response->assertViewIs('ingredientBatch.index');
        $response->assertViewHas('ingredientBatches');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('ingredient-batch.create'));

        $response->assertOk();
        $response->assertViewIs('ingredientBatch.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InputBatchController::class,
            'store',
            \App\Http\Requests\IngredientBatchStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $ingredient_id = $this->faker->numberBetween(-10000, 10000);
        $date_supplied = $this->faker->dateTime();
        $quantity_purchased = $this->faker->numberBetween(-10000, 10000);
        $quantity_remaining = $this->faker->numberBetween(-10000, 10000);
        $pack_size_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('ingredient-batch.store'), [
            'ingredient_id' => $ingredient_id,
            'date_supplied' => $date_supplied,
            'quantity_purchased' => $quantity_purchased,
            'quantity_remaining' => $quantity_remaining,
            'pack_size_id' => $pack_size_id,
        ]);

        $ingredientBatches = IngredientBatch::query()
            ->where('ingredient_id', $ingredient_id)
            ->where('date_supplied', $date_supplied)
            ->where('quantity_purchased', $quantity_purchased)
            ->where('quantity_remaining', $quantity_remaining)
            ->where('pack_size_id', $pack_size_id)
            ->get();
        $this->assertCount(1, $ingredientBatches);
        $ingredientBatch = $ingredientBatches->first();

        $response->assertRedirect(route('ingredientBatch.index'));
        $response->assertSessionHas('ingredientBatch.id', $ingredientBatch->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $ingredientBatch = IngredientBatch::factory()->create();

        $response = $this->get(route('ingredient-batch.show', $ingredientBatch));

        $response->assertOk();
        $response->assertViewIs('ingredientBatch.show');
        $response->assertViewHas('ingredientBatch');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $ingredientBatch = IngredientBatch::factory()->create();

        $response = $this->get(route('ingredient-batch.edit', $ingredientBatch));

        $response->assertOk();
        $response->assertViewIs('ingredientBatch.edit');
        $response->assertViewHas('ingredientBatch');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InputBatchController::class,
            'update',
            \App\Http\Requests\IngredientBatchUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $ingredientBatch = IngredientBatch::factory()->create();
        $ingredient_id = $this->faker->numberBetween(-10000, 10000);
        $date_supplied = $this->faker->dateTime();
        $quantity_purchased = $this->faker->numberBetween(-10000, 10000);
        $quantity_remaining = $this->faker->numberBetween(-10000, 10000);
        $pack_size_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('ingredient-batch.update', $ingredientBatch), [
            'ingredient_id' => $ingredient_id,
            'date_supplied' => $date_supplied,
            'quantity_purchased' => $quantity_purchased,
            'quantity_remaining' => $quantity_remaining,
            'pack_size_id' => $pack_size_id,
        ]);

        $ingredientBatch->refresh();

        $response->assertRedirect(route('ingredientBatch.index'));
        $response->assertSessionHas('ingredientBatch.id', $ingredientBatch->id);

        $this->assertEquals($ingredient_id, $ingredientBatch->ingredient_id);
        $this->assertEquals($date_supplied, $ingredientBatch->date_supplied);
        $this->assertEquals($quantity_purchased, $ingredientBatch->quantity_purchased);
        $this->assertEquals($quantity_remaining, $ingredientBatch->quantity_remaining);
        $this->assertEquals($pack_size_id, $ingredientBatch->pack_size_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $ingredientBatch = IngredientBatch::factory()->create();

        $response = $this->delete(route('ingredient-batch.destroy', $ingredientBatch));

        $response->assertRedirect(route('ingredientBatch.index'));

        $this->assertModelMissing($ingredientBatch);
    }
}
