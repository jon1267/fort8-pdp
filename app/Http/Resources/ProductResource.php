<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            //'aroma_id' => $this->aroma_id,
            //'brand_id' => $this->brand_id,
            'vendor' => $this->vendor,
            'name' => $this->name,
            'description' => $this->description,
            'img' => $this->img,
            'categories' => $this->categories->implode('name', ', '),
            'notes' => $this->notes->implode('name_ru', ', '),
            'notes2' => $this->notes2->implode('name_ru', ', '),
            'notes3' => $this->notes3->implode('name_ru', ', '),
            'variants' => $this->productVariants,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
