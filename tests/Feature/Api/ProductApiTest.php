<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products_via_api(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'stock', 'status', 'created_at', 'updated_at'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_store_product_via_api(): void
    {
        $payload = [
            'name' => 'Silla Ergonomica',
            'description' => 'Silla de oficina con soporte lumbar',
            'price' => 199.90,
            'stock' => 5,
            'status' => 'active',
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'Silla Ergonomica',
                'price' => 199.90,
                'stock' => 10,
                'status' => 'active',
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Silla Ergonomica',
        ]);
    }

    public function test_store_product_validation_fails_via_api(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'stock', 'status']);
    }

    public function test_can_show_product_via_api(): void
    {
        $product = Product::factory()->create([
            'name' => 'Disco SSD 1TB',
            'price' => 79.99,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => 'Disco SSD 1TB',
                'price' => 79.99,
            ]);
    }

    public function test_can_update_product_via_api(): void
    {
        $product = Product::factory()->create([
            'name' => 'Microfono USB',
            'price' => 45.00,
        ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'price' => 49.99,
            'stock' => 20,
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => 'Microfono USB',
                'price' => 49.99,
                'stock' => 20,
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 49.99,
            'stock' => 20,
        ]);
    }

    public function test_can_destroy_product_via_api(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
