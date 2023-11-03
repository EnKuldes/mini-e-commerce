<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use \App\Models\Order;
use Illuminate\Support\Facades\Schema;

class OrderTest extends TestCase
{
    // Test Schema nya
    public function testOrderSchema()
    {
        $this->assertTrue(Schema::hasColumns('orders', [
            'id','user_id', 'total_price', 'order_date', 'order_status'
        ]), 1);
    }

    // Test Atribute
    public function testOrderHasAttributes(): void
    {
        $data = new Order;
        $data->user_id =  (string) "1";
        $this->assertTrue(is_numeric($data->user_id));
        $data->user_id = 1;
        $this->assertTrue(is_numeric($data->user_id));

        $data->total_price = (string) '100000';
        $this->assertTrue(is_numeric($data->total_price));
        $data->total_price = 100000;
        $this->assertTrue(is_numeric($data->total_price));

        $this->assertFalse(is_string($data->order_date));
        // $data->order_date = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        // $this->assertFalse(is_string($data->order_date));
        $data->order_date = "2023-11-03 00:40:00";
        $this->assertTrue($data->order_date instanceof \Carbon\Carbon);

        $data->order_status = 'created';
        $this->assertEquals('created', $data->order_status);
    }

    // Test Relationship nya
    public function testOrderRelationship()
    {
        // Ga mungkin terjadi
        $data = Order::whereDoesntHave('user')->first();
        $this->assertNull($data);

        $data = Order::whereHas('user')->first();
        $this->assertTrue(!empty($data->user));

        $data = Order::whereDoesntHave('orderDetails')->first();
        $this->assertNull($data);

        $data = Order::whereHas('orderDetails')->first();
        $this->assertTrue(count($data->orderDetails) > 0);
    }
}
