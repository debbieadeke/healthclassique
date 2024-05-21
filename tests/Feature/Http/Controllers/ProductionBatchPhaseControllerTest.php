<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ProductionOrderPhase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductionBatchPhaseController
 */
class ProductionBatchPhaseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $productionBatchPhases = ProductionOrderPhase::factory()->count(3)->create();

        $response = $this->get(route('production-batch-phase.index'));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhase.index');
        $response->assertViewHas('productionBatchPhases');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('production-batch-phase.create'));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhase.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionBatchPhaseController::class,
            'store',
            \App\Http\Requests\ProductionBatchPhaseStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $production_batch_id = $this->faker->numberBetween(-10000, 10000);
        $phase_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('production-batch-phase.store'), [
            'production_batch_id' => $production_batch_id,
            'phase_id' => $phase_id,
        ]);

        $productionBatchPhases = ProductionOrderPhase::query()
            ->where('production_batch_id', $production_batch_id)
            ->where('phase_id', $phase_id)
            ->get();
        $this->assertCount(1, $productionBatchPhases);
        $productionBatchPhase = $productionBatchPhases->first();

        $response->assertRedirect(route('productionBatchPhase.index'));
        $response->assertSessionHas('productionBatchPhase.id', $productionBatchPhase->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $productionBatchPhase = ProductionOrderPhase::factory()->create();

        $response = $this->get(route('production-batch-phase.show', $productionBatchPhase));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhase.show');
        $response->assertViewHas('productionBatchPhase');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $productionBatchPhase = ProductionOrderPhase::factory()->create();

        $response = $this->get(route('production-batch-phase.edit', $productionBatchPhase));

        $response->assertOk();
        $response->assertViewIs('productionBatchPhase.edit');
        $response->assertViewHas('productionBatchPhase');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductionBatchPhaseController::class,
            'update',
            \App\Http\Requests\ProductionBatchPhaseUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $productionBatchPhase = ProductionOrderPhase::factory()->create();
        $production_batch_id = $this->faker->numberBetween(-10000, 10000);
        $phase_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('production-batch-phase.update', $productionBatchPhase), [
            'production_batch_id' => $production_batch_id,
            'phase_id' => $phase_id,
        ]);

        $productionBatchPhase->refresh();

        $response->assertRedirect(route('productionBatchPhase.index'));
        $response->assertSessionHas('productionBatchPhase.id', $productionBatchPhase->id);

        $this->assertEquals($production_batch_id, $productionBatchPhase->production_batch_id);
        $this->assertEquals($phase_id, $productionBatchPhase->phase_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $productionBatchPhase = ProductionOrderPhase::factory()->create();

        $response = $this->delete(route('production-batch-phase.destroy', $productionBatchPhase));

        $response->assertRedirect(route('productionBatchPhase.index'));

        $this->assertModelMissing($productionBatchPhase);
    }
}
