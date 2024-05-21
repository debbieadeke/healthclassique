<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SupplierController
 */
class SupplierControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->get(route('suppliers.index'));

        $response->assertOk();
        $response->assertViewIs('suppliers.index');
        $response->assertViewHas('suppliers');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('suppliers.create'));

        $response->assertOk();
        $response->assertViewIs('suppliers.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SupplierController::class,
            'store',
            \App\Http\Requests\SupplierStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;

        $response = $this->post(route('suppliers.store'), [
            'name' => $name,
        ]);

        $suppliers = Supplier::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $suppliers);
        $supplier = $suppliers->first();

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('suppliers.id', $supplier->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.show', $supplier));

        $response->assertOk();
        $response->assertViewIs('suppliers.show');
        $response->assertViewHas('suppliers');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.edit', $supplier));

        $response->assertOk();
        $response->assertViewIs('suppliers.edit');
        $response->assertViewHas('suppliers');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SupplierController::class,
            'update',
            \App\Http\Requests\SupplierUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $supplier = Supplier::factory()->create();
        $name = $this->faker->name;

        $response = $this->put(route('suppliers.update', $supplier), [
            'name' => $name,
        ]);

        $supplier->refresh();

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('suppliers.id', $supplier->id);

        $this->assertEquals($name, $supplier->name);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->delete(route('suppliers.destroy', $supplier));

        $response->assertRedirect(route('suppliers.index'));

        $this->assertModelMissing($supplier);
    }
}
