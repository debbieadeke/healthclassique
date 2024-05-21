<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ProductionOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductionOrderController
 */
class ProductionBatchDetailControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $productionBatchDetails = ProductionOrder::factory()->count(3)->create();

        $response = $this->get(route('production-batch-detail.index'));

        $response->assertOk();
        $response->assertViewIs('productionBatchDetail.index');
        $response->assertViewHas('productionBatchDetails');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('production-batch-detail.create'));

        $response->assertOk();
        $response->assertViewIs('productionBatchDetail.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionOrderController::class,
            'store',
            \App\Http\Requests\ProductionBatchDetailStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $production_batch_id = $this->faker->numberBetween(-10000, 10000);
        $batch_quantity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('production-batch-detail.store'), [
            'production_batch_id' => $production_batch_id,
            'batch_quantity' => $batch_quantity,
        ]);

        $productionBatchDetails = ProductionOrder::query()
            ->where('production_batch_id', $production_batch_id)
            ->where('batch_quantity', $batch_quantity)
            ->get();
        $this->assertCount(1, $productionBatchDetails);
        $productionBatchDetail = $productionBatchDetails->first();

        $response->assertRedirect(route('productionBatchDetail.index'));
        $response->assertSessionHas('productionBatchDetail.id', $productionBatchDetail->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $productionBatchDetail = ProductionOrder::factory()->create();

        $response = $this->get(route('production-batch-detail.show', $productionBatchDetail));

        $response->assertOk();
        $response->assertViewIs('productionBatchDetail.show');
        $response->assertViewHas('productionBatchDetail');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $productionBatchDetail = ProductionOrder::factory()->create();

        $response = $this->get(route('production-batch-detail.edit', $productionBatchDetail));

        $response->assertOk();
        $response->assertViewIs('productionBatchDetail.edit');
        $response->assertViewHas('productionBatchDetail');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionOrderController::class,
            'update',
            \App\Http\Requests\ProductionBatchDetailUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $productionBatchDetail = ProductionOrder::factory()->create();
        $production_batch_id = $this->faker->numberBetween(-10000, 10000);
        $batch_quantity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('production-batch-detail.update', $productionBatchDetail), [
            'production_batch_id' => $production_batch_id,
            'batch_quantity' => $batch_quantity,
        ]);

        $productionBatchDetail->refresh();

        $response->assertRedirect(route('productionBatchDetail.index'));
        $response->assertSessionHas('productionBatchDetail.id', $productionBatchDetail->id);

        $this->assertEquals($production_batch_id, $productionBatchDetail->production_batch_id);
        $this->assertEquals($batch_quantity, $productionBatchDetail->batch_quantity);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $productionBatchDetail = ProductionOrder::factory()->create();

        $response = $this->delete(route('production-batch-detail.destroy', $productionBatchDetail));

        $response->assertRedirect(route('productionBatchDetail.index'));

        $this->assertModelMissing($productionBatchDetail);
    }
}
