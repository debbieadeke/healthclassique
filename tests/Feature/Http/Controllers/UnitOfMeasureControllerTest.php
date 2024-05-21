<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\UnitOfMeasure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UnitOfMeasureController
 */
class UnitOfMeasureControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $unitOfMeasures = UnitOfMeasure::factory()->count(3)->create();

        $response = $this->get(route('unit-of-measure.index'));

        $response->assertOk();
        $response->assertViewIs('unitOfMeasure.index');
        $response->assertViewHas('unitOfMeasures');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('unit-of-measure.create'));

        $response->assertOk();
        $response->assertViewIs('unitOfMeasure.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UnitOfMeasureController::class,
            'store',
            \App\Http\Requests\UnitOfMeasureStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;

        $response = $this->post(route('unit-of-measure.store'), [
            'name' => $name,
        ]);

        $unitOfMeasures = UnitOfMeasure::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $unitOfMeasures);
        $unitOfMeasure = $unitOfMeasures->first();

        $response->assertRedirect(route('unitOfMeasure.index'));
        $response->assertSessionHas('unitOfMeasure.id', $unitOfMeasure->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $unitOfMeasure = UnitOfMeasure::factory()->create();

        $response = $this->get(route('unit-of-measure.show', $unitOfMeasure));

        $response->assertOk();
        $response->assertViewIs('unitOfMeasure.show');
        $response->assertViewHas('unitOfMeasure');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $unitOfMeasure = UnitOfMeasure::factory()->create();

        $response = $this->get(route('unit-of-measure.edit', $unitOfMeasure));

        $response->assertOk();
        $response->assertViewIs('unitOfMeasure.edit');
        $response->assertViewHas('unitOfMeasure');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UnitOfMeasureController::class,
            'update',
            \App\Http\Requests\UnitOfMeasureUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $unitOfMeasure = UnitOfMeasure::factory()->create();
        $name = $this->faker->name;

        $response = $this->put(route('unit-of-measure.update', $unitOfMeasure), [
            'name' => $name,
        ]);

        $unitOfMeasure->refresh();

        $response->assertRedirect(route('unitOfMeasure.index'));
        $response->assertSessionHas('unitOfMeasure.id', $unitOfMeasure->id);

        $this->assertEquals($name, $unitOfMeasure->name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $unitOfMeasure = UnitOfMeasure::factory()->create();

        $response = $this->delete(route('unit-of-measure.destroy', $unitOfMeasure));

        $response->assertRedirect(route('unitOfMeasure.index'));

        $this->assertModelMissing($unitOfMeasure);
    }
}
