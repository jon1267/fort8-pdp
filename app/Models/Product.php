<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'aroma_id', 'brand_id' ,'vendor', 'name', 'description', 'description_ua',
        'img', 'img1', 'img2', 'hide' ,'created_by_id', 'updated_by_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function notes2()
    {
        return $this->belongsToMany(Note::class, 'note2_product');
    }

    public function notes3()
    {
        return $this->belongsToMany(Note::class, 'note3_product');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
