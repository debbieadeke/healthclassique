<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Phase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PhaseController
 */
class PhaseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $phases = Phase::factory()->count(3)->create();

        $response = $this->get(route('phase.index'));

        $response->assertOk();
        $response->assertViewIs('phase.index');
        $response->assertViewHas('phases');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('phase.create'));

        $response->assertOk();
        $response->assertViewIs('phase.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PhaseController::class,
            'store',
            \App\Http\Requests\PhaseStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $type = $this->faker->randomElement(/** enum_attributes **/);

        $response = $this->post(route('phase.store'), [
            'name' => $name,
            'type' => $type,
        ]);

        $phases = Phase::query()
            ->where('name', $name)
            ->where('type', $type)
            ->get();
        $this->assertCount(1, $phases);
        $phase = $phases->first();

        $response->assertRedirect(route('phase.index'));
        $response->assertSessionHas('phase.id', $phase->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $phase = Phase::factory()->create();

        $response = $this->get(route('phase.show', $phase));

        $response->assertOk();
        $response->assertViewIs('phase.show');
        $response->assertViewHas('phase');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $phase = Phase::factory()->create();

        $response = $this->get(route('phase.edit', $phase));

        $response->assertOk();
        $response->assertViewIs('phase.edit');
        $response->assertViewHas('phase');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PhaseController::class,
            'update',
            \App\Http\Requests\PhaseUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $phase = Phase::factory()->create();
        $name = $this->faker->name;
        $type = $this->faker->randomElement(/** enum_attributes **/);

        $response = $this->put(route('phase.update', $phase), [
            'name' => $name,
            'type' => $type,
        ]);

        $phase->refresh();

        $response->assertRedirect(route('phase.index'));
        $response->assertSessionHas('phase.id', $phase->id);

        $this->assertEquals($name, $phase->name);
        $this->assertEquals($type, $phase->type);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $phase = Phase::factory()->create();

        $response = $this->delete(route('phase.destroy', $phase));

        $response->assertRedirect(route('phase.index'));

        $this->assertModelMissing($phase);
    }
}
