<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\PackSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PackSizeController
 */
class PackSizeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $packSizes = PackSize::factory()->count(3)->create();

        $response = $this->get(route('pack-size.index'));

        $response->assertOk();
        $response->assertViewIs('packSize.index');
        $response->assertViewHas('packSizes');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('pack-size.create'));

        $response->assertOk();
        $response->assertViewIs('packSize.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PackSizeController::class,
            'store',
            \App\Http\Requests\PackSizeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;

        $response = $this->post(route('pack-size.store'), [
            'name' => $name,
        ]);

        $packSizes = PackSize::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $packSizes);
        $packSize = $packSizes->first();

        $response->assertRedirect(route('packSize.index'));
        $response->assertSessionHas('packSize.id', $packSize->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $packSize = PackSize::factory()->create();

        $response = $this->get(route('pack-size.show', $packSize));

        $response->assertOk();
        $response->assertViewIs('packSize.show');
        $response->assertViewHas('packSize');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $packSize = PackSize::factory()->create();

        $response = $this->get(route('pack-size.edit', $packSize));

        $response->assertOk();
        $response->assertViewIs('packSize.edit');
        $response->assertViewHas('packSize');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PackSizeController::class,
            'update',
            \App\Http\Requests\PackSizeUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $packSize = PackSize::factory()->create();
        $name = $this->faker->name;

        $response = $this->put(route('pack-size.update', $packSize), [
            'name' => $name,
        ]);

        $packSize->refresh();

        $response->assertRedirect(route('packSize.index'));
        $response->assertSessionHas('packSize.id', $packSize->id);

        $this->assertEquals($name, $packSize->name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $packSize = PackSize::factory()->create();

        $response = $this->delete(route('pack-size.destroy', $packSize));

        $response->assertRedirect(route('packSize.index'));

        $this->assertModelMissing($packSize);
    }
}
