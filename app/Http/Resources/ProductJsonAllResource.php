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
            'active' => '0',
            'active_ua' => $this->getVariantData($this->productVariants, 25)['active_ua'],
            'active_ru' => $this->getVariantData($this->productVariants, 25)['active_ru'],
            'active25ru' => $this->getVariantData($this->productVariants, 25)['active_ru'],
            'active50ru' => $this->getVariantData($this->productVariants, 50)['active_ru'],
            'active100ru' => $this->getVariantData($this->productVariants, 100)['active_ru'],
            'active_amator' => '0',
            'sort' => $this->sort,
            'hide' => $this->hide,
            'art' => '',
            'bname' => $this->brand->name,
            'name' => $this->name,
            'text' => $this->description,
            'skidka' => '0',
            'count' => '0',
            'price' => '0',
            'pricestore' => '0',
            'price_ru' => '0',
            'pricestore_ru' => '0',
            'img' => str_replace('/images/product/','',$this->img),
            'active_hit' => '0',
            'active_action' => '0',
            'samples' => '0',
            'sort' => $this->sort,
            'filters' => $this->notes->implode('name_ru', ', '), //'notes'
            //предполагается: 1-женские парфюмы, 2-мужские, 3-антисептики, 4-автопарфюмы, 5-спреи д.волос, 6,7 годовой запас
            //если эти 'id' меняются в таблице категорий, нужно соотв. поменять и сдесь...
            'man' => in_array(2, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'woman' => in_array(1, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'auto' => in_array(4, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'antiseptics' => in_array(3, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'hair_spray' => in_array(5, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'man500' => in_array(7, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'woman500' => in_array(6, $this->categories->pluck('id')->toArray()) ? '1' : '0',
            'price25' => $this->getVariantData($this->productVariants, 25)['price_ua'],
            'art25' => $this->getVariantData($this->productVariants, 25)['art'],
            'price50' => $this->getVariantData($this->productVariants, 50)['price_ua'],
            'price50ru' => $this->getVariantData($this->productVariants, 50)['price_ru'],
            'art50' => $this->getVariantData($this->productVariants, 50)['art'],
            'price100' => $this->getVariantData($this->productVariants, 100)['price_ua'],
            'price100ru' => $this->getVariantData($this->productVariants, 100)['price_ru'],
            'art100' => $this->getVariantData($this->productVariants, 100)['art'],

            // счтаем устаревшими...
            //$upd['pricestore'] = $product['pricestore'];
            //$upd['active_hit'] = $product['active_hit'];
            //$upd['active_action'] = $product['active_action'];
            //$upd['skidka'] = $product['skidka'];
            // $upd['active'] = $product['active'];

            //'variants' => $this->productVariants,
            //'price25' => $this->productVariants,
            //'categories' => $this->categories->implode('id', ', '),
            //'vendor' => $this->vendor,
            //'aroma_id' => $this->aroma_id,
            //'img2' => $this->img2,
            //'img3' => $this->img3,
            //'created_by_id' => $this->created_by_id,
            //'updated_by_id' => $this->updated_by_id,
            //'description_ua' => $this->description_ua,
            //'notes2' => $this->notes2->implode('id', ', '),
            //'notes3' => $this->notes3->implode('id', ', '),
            //'variants' => $this->productVariants,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }

    private function getVariantData($productVariant, $volume)
    {
        $result['art'] = '';
        $result['price_ua'] = $result['price_ru'] = $result['active_ua'] = $result['active_ru'] = '0';

        foreach ($productVariant as $variant)
        {
            //dd($variant, gettype($variant));
            if ($variant->volume == $volume) {
                $result['art'] = $variant->art;
                $result['price_ua'] = (string) $variant->price_ua;
                $result['price_ru'] = (string) $variant->price_ru;
                $result['active_ua'] = (string) $variant->active_ua;
                $result['active_ru'] = (string) $variant->active_ru;
            }
        }

        return $result;
    }
}
