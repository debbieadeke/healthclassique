<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CategoryController
 */
class CategoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('category.index'));

        $response->assertOk();
        $response->assertViewIs('category.index');
        $response->assertViewHas('categories');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('category.create'));

        $response->assertOk();
        $response->assertViewIs('category.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CategoryController::class,
            'store',
            \App\Http\Requests\CategoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $parent_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('category.store'), [
            'name' => $name,
            'parent_id' => $parent_id,
        ]);

        $categories = Category::query()
            ->where('name', $name)
            ->where('parent_id', $parent_id)
            ->get();
        $this->assertCount(1, $categories);
        $category = $categories->first();

        $response->assertRedirect(route('category.index'));
        $response->assertSessionHas('category.id', $category->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('category.show', $category));

        $response->assertOk();
        $response->assertViewIs('category.show');
        $response->assertViewHas('category');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('category.edit', $category));

        $response->assertOk();
        $response->assertViewIs('category.edit');
        $response->assertViewHas('category');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CategoryController::class,
            'update',
            \App\Http\Requests\CategoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $category = Category::factory()->create();
        $name = $this->faker->name;
        $parent_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('category.update', $category), [
            'name' => $name,
            'parent_id' => $parent_id,
        ]);

        $category->refresh();

        $response->assertRedirect(route('category.index'));
        $response->assertSessionHas('category.id', $category->id);

        $this->assertEquals($name, $category->name);
        $this->assertEquals($parent_id, $category->parent_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('category.destroy', $category));

        $response->assertRedirect(route('category.index'));

        $this->assertModelMissing($category);
    }
}
