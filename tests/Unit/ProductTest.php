<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use \App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ProductTest extends TestCase
{

    // Test Schema nya
    public function testProductSchema()
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'id','name', 'description', 'price', 'images'
        ]), 1);
    }

    // Test Atribute
    public function testProductHasAttributes(): void
    {
        $data = new Product;
        $this->assertFalse(is_string($data->name));
        $data->name = "Test";
        $this->assertTrue(is_string($data->name));

        $this->assertFalse(is_string($data->description));
        $data->description = "Test description";
        $this->assertTrue(is_string($data->description));

        $this->assertFalse(is_int($data->price));
        $data->price = 50000;
        $this->assertTrue(is_int($data->price));

        $this->assertFalse(is_array($data->images));
        $data->images = [];
        $this->assertTrue(is_array($data->images));
        $data->images = ['path-to-image'];
        $this->assertTrue(count($data->images) > 0);
        $this->assertFalse(count($data->images) == 0);
    }

    // Test Relationship nya
    public function testProductRelationship()
    {
        $data = Product::whereDoesntHave('orderDetails')->first();
        $this->assertTrue(count($data->orderDetails) == 0);

        $data = Product::whereHas('orderDetails')->first();
        $this->assertTrue(count($data->orderDetails) > 0);
    }

}
