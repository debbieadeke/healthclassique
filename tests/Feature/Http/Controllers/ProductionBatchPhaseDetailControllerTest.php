<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ProductionOrderPhaseDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductionOrderPhaseDetailController
 */
class ProductionBatchPhaseDetailControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $productionBatchPhaseDetails = ProductionOrderPhaseDetail::factory()->count(3)->create();

        $response = $this->get(route('production-batch-phase-detail.index'));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhaseDetail.index');
        $response->assertViewHas('productionBatchPhaseDetails');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('production-batch-phase-detail.create'));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhaseDetail.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionOrderPhaseDetailController::class,
            'store',
            \App\Http\Requests\ProductionBatchPhaseDetailStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $production_batch_phase_id = $this->faker->numberBetween(-10000, 10000);
        $product_id = $this->faker->numberBetween(-10000, 10000);
        $percentage = $this->faker->numberBetween(-1000, 1000);
        $weight = $this->faker->numberBetween(-10000, 10000);
        $pack_size_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('production-batch-phase-detail.store'), [
            'production_batch_phase_id' => $production_batch_phase_id,
            'product_id' => $product_id,
            'percentage' => $percentage,
            'weight' => $weight,
            'pack_size_id' => $pack_size_id,
        ]);

        $productionBatchPhaseDetails = ProductionOrderPhaseDetail::query()
            ->where('production_batch_phase_id', $production_batch_phase_id)
            ->where('product_id', $product_id)
            ->where('percentage', $percentage)
            ->where('weight', $weight)
            ->where('pack_size_id', $pack_size_id)
            ->get();
        $this->assertCount(1, $productionBatchPhaseDetails);
        $productionBatchPhaseDetail = $productionBatchPhaseDetails->first();

        $response->assertRedirect(route('productionBatchPhaseDetail.index'));
        $response->assertSessionHas('productionBatchPhaseDetail.id', $productionBatchPhaseDetail->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $productionBatchPhaseDetail = ProductionOrderPhaseDetail::factory()->create();

        $response = $this->get(route('production-batch-phase-detail.show', $productionBatchPhaseDetail));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhaseDetail.show');
        $response->assertViewHas('productionBatchPhaseDetail');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $productionBatchPhaseDetail = ProductionOrderPhaseDetail::factory()->create();

        $response = $this->get(route('production-batch-phase-detail.edit', $productionBatchPhaseDetail));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhaseDetail.edit');
        $response->assertViewHas('productionBatchPhaseDetail');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionOrderPhaseDetailController::class,
            'update',
            \App\Http\Requests\ProductionBatchPhaseDetailUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $productionBatchPhaseDetail = ProductionOrderPhaseDetail::factory()->create();
        $production_batch_phase_id = $this->faker->numberBetween(-10000, 10000);
        $product_id = $this->faker->numberBetween(-10000, 10000);
        $percentage = $this->faker->numberBetween(-1000, 1000);
        $weight = $this->faker->numberBetween(-10000, 10000);
        $pack_size_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('production-batch-phase-detail.update', $productionBatchPhaseDetail), [
            'production_batch_phase_id' => $production_batch_phase_id,
            'product_id' => $product_id,
            'percentage' => $percentage,
            'weight' => $weight,
            'pack_size_id' => $pack_size_id,
        ]);

        $productionBatchPhaseDetail->refresh();

        $response->assertRedirect(route('productionBatchPhaseDetail.index'));
        $response->assertSessionHas('productionBatchPhaseDetail.id', $productionBatchPhaseDetail->id);

        $this->assertEquals($production_batch_phase_id, $productionBatchPhaseDetail->production_batch_phase_id);
        $this->assertEquals($product_id, $productionBatchPhaseDetail->product_id);
        $this->assertEquals($percentage, $productionBatchPhaseDetail->percentage);
        $this->assertEquals($weight, $productionBatchPhaseDetail->weight);
        $this->assertEquals($pack_size_id, $productionBatchPhaseDetail->pack_size_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $productionBatchPhaseDetail = ProductionOrderPhaseDetail::factory()->create();

        $response = $this->delete(route('production-batch-phase-detail.destroy', $productionBatchPhaseDetail));

        $response->assertRedirect(route('productionBatchPhaseDetail.index'));

        $this->assertModelMissing($productionBatchPhaseDetail);
    }
}
