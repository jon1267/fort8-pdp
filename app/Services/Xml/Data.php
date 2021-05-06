<?php

namespace App\Services\Xml;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Prepare products data for generate xml files (PromUa, Google)
 * Need because data almost repeat for PromUa & Google
 *
 * Class Data
 * @package App\Services\Xml
 */
class Data
{
    public function products()
    {
        //all data for create promUa & google xml-file; here only Man & Woman parfume volume 50 or 100 ml
        $prods = DB::table('product_variants')
            ->leftJoin('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('category_product', 'products.id', '=', 'category_product.product_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                'products.id', 'products.vendor', 'products.name as name', 'products.description', 'products.img' ,'products.img2',
                'product_variants.name as variant', 'product_variants.volume as volume','product_variants.active_ua',
                'product_variants.price_ua', 'category_product.category_id as category_id', 'brands.name as brand'
            )
            ->whereIn('product_variants.volume', [50.00, 100.00])
            ->where('product_variants.active_ua', '=', 1)
            ->whereIn('category_product.category_id', [1,2])
            ->orderBy('category_id')
            ->orderBy('volume')
            ->get();
        //dd($prods);

        $products =[];
        //$newMainNotes = 'Основные ноты';//'Основные аккорды'
        $newStartNote = 'Начальная нота';
        $newHeartNote = 'Нота сердца';
        $newFinishNote = 'Конечная нота';
        $newMiddleNote = 'Нота сердца';
        $newFinalNote = 'Конечная нота';
        $newNoBr = '';
        $new = [$newStartNote, $newHeartNote, $newFinishNote, $newMiddleNote, $newFinalNote,$newNoBr];

        //$oldMainNotes = 'Основные аккорды';
        $oldStartNote = 'Верхние ноты';
        $oldHeartNote = 'Ноты сердца';
        $oldFinishNote = 'Базовая нота';
        $oldMiddleNote = 'Средние ноты';
        $oldFinalNote = 'Базовые ноты';
        $oldBrPresent = '<br />';
        $old = [$oldStartNote, $oldHeartNote, $oldFinishNote, $oldMiddleNote, $oldFinalNote, $oldBrPresent];

        foreach ($prods as $key => $product) {
            $noteStr = $this->getProductNotes($product->id);
            $product->description = str_replace($old, $new, $product->description);
            $products[$key] = $product;
            $products[$key]->notes = $noteStr;
        }

        //dd($products);
        return $products;
    }

    private function getProductNotes(int $productId)
    {
        $product = Product::where('id', $productId)->first();
        $noteStr = '';
        if ($product->notes) {
            foreach ($product->notes as $note) {
                $noteStr .= ', ' . $note->name_ru;
            }
        }
        return $noteStr;
    }

}
