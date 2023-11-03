<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use \App\Models\OrderDetail;
use \App\Models\Product;
use \App\Models\Order;
use Illuminate\Support\Facades\Schema;

class OrderDetailTest extends TestCase
{
    public function testOrderDetailschema()
    {
        $this->assertTrue(Schema::hasColumns('order_details', [
            'id','order_id', 'product_id', 'qty', 'current_price'
        ]), 1);
    }

    // Test Atribute
    public function testOrderDetailHasAttributes(): void
    {
        $data = new OrderDetail;

        $this->assertNull($data->order_id);
        $this->assertNull($data->product_id);
        $this->assertNull($data->qty);
        $this->assertNull($data->current_price);
        
    }

    // Test Relationship nya
    public function testOrderDetailRelationship()
    {
        $data = OrderDetail::whereDoesntHave('order')->first();
        $this->assertNull($data);

        $data = OrderDetail::whereHas('order')->first();
        $this->assertTrue($data->order instanceof Order);

        $data = OrderDetail::whereDoesntHave('product')->first();
        $this->assertNull($data);

        $data = OrderDetail::whereHas('product')->first();
        $this->assertTrue($data->product instanceof Product);
    }
}
