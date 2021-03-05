<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductJsonAllResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'aroma_id' => $this->aroma_id,
            'brand_id' => $this->brand_id,
            'vendor' => $this->vendor,
            'name' => $this->name,
            'description' => $this->description,
            'description_ua' => $this->description_ua,
            'img' => $this->img,
            'img2' => $this->img2,
            'img3' => $this->img3,
            'hide' => $this->hide,
            'sort' => $this->sort,
            'created_by_id' => $this->created_by_id,
            'updated_by_id' => $this->updated_by_id,
            'categories' => $this->categories->implode('id', ', '),
            'notes' => $this->notes->implode('id', ', '),
            'notes2' => $this->notes2->implode('id', ', '),
            'notes3' => $this->notes3->implode('id', ', '),
            'variants' => $this->productVariants,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
