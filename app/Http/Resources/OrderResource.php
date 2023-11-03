<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            , 'total_price' => number_format($this->total_price, 2)
            , 'order_date' => $this->order_date
            , 'order_status' => $this->order_status
            , 'details' => OrderDetailResource::collection($this->whenLoaded('orderDetails'))
        ];
    }
}
