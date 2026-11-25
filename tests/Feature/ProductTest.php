<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee($products[0]->name);
        $response->assertSee($products[1]->name);
        $response->assertSee($products[2]->name);
    }

    public function test_can_filter_products_by_search_and_status(): void
    {
        Product::factory()->create(['name' => 'Teclado Mecanico', 'status' => 'active']);
        Product::factory()->create(['name' => 'Mouse Gamer', 'status' => 'inactive']);

        $response = $this->get(route('products.index', ['search' => 'Teclado', 'status' => 'active']));

        $response->assertOk();
        $response->assertSee('Teclado Mecanico');
        $response->assertDontSee('Mouse Gamer');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get(route('products.create'));

        $response->assertOk();
        $response->assertSee('Crear Nuevo Producto');
    }

    public function test_can_store_product(): void
    {
        $payload = [
            'name' => 'Monitor 27 Pulgadas',
            'description' => 'Monitor IPS 144Hz',
            'price' => 299.99,
            'stock' => 15,
            'status' => 'active',
        ];

        $response = $this->post(route('products.store'), $payload);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Monitor 27 Pulgadas',
            'price' => 299.99,
            'stock' => 15,
            'status' => 'active',
        ]);
    }

    public function test_store_product_validation_errors(): void
    {
        $response = $this->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'stock', 'status']);
    }

    public function test_can_render_show_page(): void
    {
        $product = Product::factory()->create([
            'name' => 'Auriculares Inalambricos',
            'price' => 89.50,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Auriculares Inalambricos');
        $response->assertSee('$89.50');
    }

    public function test_can_render_edit_page(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.edit', $product));

        $response->assertOk();
        $response->assertSee('Editar Producto');
        $response->assertSee($product->name);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Nombre Viejo',
            'price' => 50.00,
        ]);

        $response = $this->put(route('products.update', $product), [
            'name' => 'Nombre Actualizado',
            'price' => 75.00,
            'stock' => 8,
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nombre Actualizado',
            'price' => 75.00,
            'stock' => 8,
            'status' => 'inactive',
        ]);
    }

    public function test_can_destroy_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
