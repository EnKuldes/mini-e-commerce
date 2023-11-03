<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id
            , 'order_id' => $this->order_id
            , 'product_id' => $this->product_id
            , 'qty' => $this->qty
            , 'current_price' => number_format($this->current_price,2)
            , 'product' => new ProductResource($this->product)
        ];
    }
}
