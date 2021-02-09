<?php

namespace App\Services\Variants;

use App\Models\ProductVariant;

class Variants
{
    private $variant;

    public function __construct(ProductVariant $variant)
    {
        $this->variant = $variant;
    }

    public function store($variations, int $productId)
    {
        //dd($variations, count($variations),  $variations['variants']);
        //при редактировании товара, не вводили вариации (устраивают старые), и из JS прилетает ['variants' => null]
        if (count($variations) == 1 && $variations['variants'] == null) {
            return;
        }

        if (is_array($variations) && count($variations)) {

            $variants = [];
            $i=0;
            foreach ($variations as $items) {
               foreach ($items as $rows) {
                   $variants[$i]['product_id'] = $productId;
                   foreach ($rows as $key => $value) {
                       $variants[$i][$key] = $value;
                   }

                   if (!isset($variants[$i]['active_ua'])) {
                       $variants[$i]['active_ua'] = 0;
                   }
                   if (!isset($variants[$i]['active_ru'])) {
                       $variants[$i]['active_ru'] = 0;
                   }
                   // а попробую я тут сделать created updated at(by_id)
                   $now = date('Y-m-d H:i:s');
                   $useId = auth()->user()->id;
                   $variants[$i]['created_at'] = $now;
                   $variants[$i]['updated_at'] = $now;
                   $variants[$i]['created_by_id'] = $useId;
                   $variants[$i]['updated_by_id'] = $useId;

                   $i++;
               }
            }

            //dd($variants);

            $this->variant->deleteProductVariantsBy($productId);
            ProductVariant::insert($variants);
        }
    }

    public function deleteVariants($productId)
    {
        $this->variant->deleteProductVariantsBy($productId);
    }
}
