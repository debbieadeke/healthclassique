<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BrandController
 */
class BrandControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $brands = Brand::factory()->count(3)->create();

        $response = $this->get(route('brand.index'));

        $response->assertOk();
        $response->assertViewIs('brand.index');
        $response->assertViewHas('brands');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('brand.create'));

        $response->assertOk();
        $response->assertViewIs('brand.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BrandController::class,
            'store',
            \App\Http\Requests\BrandStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;

        $response = $this->post(route('brand.store'), [
            'name' => $name,
        ]);

        $brands = Brand::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $brands);
        $brand = $brands->first();

        $response->assertRedirect(route('brand.index'));
        $response->assertSessionHas('brand.id', $brand->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->get(route('brand.show', $brand));

        $response->assertOk();
        $response->assertViewIs('brand.show');
        $response->assertViewHas('brand');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->get(route('brand.edit', $brand));

        $response->assertOk();
        $response->assertViewIs('brand.edit');
        $response->assertViewHas('brand');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BrandController::class,
            'update',
            \App\Http\Requests\BrandUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $brand = Brand::factory()->create();
        $name = $this->faker->name;

        $response = $this->put(route('brand.update', $brand), [
            'name' => $name,
        ]);

        $brand->refresh();

        $response->assertRedirect(route('brand.index'));
        $response->assertSessionHas('brand.id', $brand->id);

        $this->assertEquals($name, $brand->name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->delete(route('brand.destroy', $brand));

        $response->assertRedirect(route('brand.index'));

        $this->assertModelMissing($brand);
    }
}
