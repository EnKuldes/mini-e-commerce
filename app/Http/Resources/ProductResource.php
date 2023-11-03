<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // handling images nya supaya bisa pake url
        $listImages = [];
        foreach ($this->images as $image) {
            $listImages[] = (\Storage::exists($image) ? \Storage::url($image) : asset('dist/img/no-photo.jpg'));
        }
        return [
            'id' => $this->id
            , 'name' => $this->name
            , 'description' => $this->description
            , 'price' => number_format($this->price,2)
            , 'images' => $listImages
        ];
    }
}
