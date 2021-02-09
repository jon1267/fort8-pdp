<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['name_ua', 'name_ru'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function products2()
    {
        return $this->belongsToMany(Product::class, 'note2_product');
    }

    public function products3()
    {
        return $this->belongsToMany(Product::class, 'note3_product');
    }
}
