<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id', 'name' ,'volume', 'art','price_ua', 'price_ru', 'active_ua', 'active_ru', 'created_by_id', 'updated_by_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // удаляем вариации (варианты) продукта по 'product_id'
    public function deleteProductVariantsBy(int $productId)
    {
        return $this->where('product_id', $productId)->delete();
    }
}
