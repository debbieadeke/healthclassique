<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ProductionSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductionBatchController
 */
class ProductionBatchControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $productionBatches = ProductionSetting::factory()->count(3)->create();

        $response = $this->get(route('production-batch.index'));

        $response->assertOk();
        $response->assertViewIs('productionBatch.index');
        $response->assertViewHas('productionBatches');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('production-batch.create'));

        $response->assertOk();
        $response->assertViewIs('productionBatch.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionBatchController::class,
            'store',
            \App\Http\Requests\ProductionBatchStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $product_id = $this->faker->numberBetween(-10000, 10000);
        $no_of_phases = $this->faker->numberBetween(-1000, 1000);

        $response = $this->post(route('production-batch.store'), [
            'product_id' => $product_id,
            'no_of_phases' => $no_of_phases,
        ]);

        $productionBatches = ProductionSetting::query()
            ->where('product_id', $product_id)
            ->where('no_of_phases', $no_of_phases)
            ->get();
        $this->assertCount(1, $productionBatches);
        $productionBatch = $productionBatches->first();

        $response->assertRedirect(route('productionBatch.index'));
        $response->assertSessionHas('productionBatch.id', $productionBatch->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $productionBatch = ProductionSetting::factory()->create();

        $response = $this->get(route('production-batch.show', $productionBatch));

        $response->assertOk();
        $response->assertViewIs('productionBatch.show');
        $response->assertViewHas('productionBatch');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $productionBatch = ProductionSetting::factory()->create();

        $response = $this->get(route('production-batch.edit', $productionBatch));

        $response->assertOk();
        $response->assertViewIs('productionBatch.edit');
        $response->assertViewHas('productionBatch');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionBatchController::class,
            'update',
            \App\Http\Requests\ProductionBatchUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $productionBatch = ProductionSetting::factory()->create();
        $product_id = $this->faker->numberBetween(-10000, 10000);
        $no_of_phases = $this->faker->numberBetween(-1000, 1000);

        $response = $this->put(route('production-batch.update', $productionBatch), [
            'product_id' => $product_id,
            'no_of_phases' => $no_of_phases,
        ]);

        $productionBatch->refresh();

        $response->assertRedirect(route('productionBatch.index'));
        $response->assertSessionHas('productionBatch.id', $productionBatch->id);

        $this->assertEquals($product_id, $productionBatch->product_id);
        $this->assertEquals($no_of_phases, $productionBatch->no_of_phases);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $productionBatch = ProductionSetting::factory()->create();

        $response = $this->delete(route('production-batch.destroy', $productionBatch));

        $response->assertRedirect(route('productionBatch.index'));

        $this->assertModelMissing($productionBatch);
    }
}
