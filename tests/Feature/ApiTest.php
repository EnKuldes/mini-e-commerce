<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Product;
use \App\Models\OrderDetail;
use \App\Models\Order;
use \App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;


class ApiTest extends TestCase
{
    public function testApiFlow()
    {
        // Buka cart tanpa login
        $response = $this->getJson('/api/orders/cart');
        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated.',
        ]);

        // login
        $response = $this->postJson('/api/login', ['email' => 'Administrator@admin.com', 'password' => '4dm1n987']);
        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['success', 'data', 'message'])
        );

        // pake user
        $user = User::inRandomOrder()->first();
        
        // tambah ke cart
        $product = Product::inRandomOrder()->first();
        $response = $this->actingAs($user, 'api')->postJson('/api/orders/cart', ['product_id' => $product->id, 'qty' => 3]);
        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['success', 'data', 'message'])
        );

        // cek cart
        $response = $this->actingAs($user, 'api')->getJson('/api/orders/cart');
        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['success', 'data', 'message'])
        );

        // checkout
        $response = $this->actingAs($user, 'api')->getJson('/api/orders/checkout');
        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['success', 'data', 'message'])
        );
    }
}
