<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ua', 'header_mobile', 'header_desktop', 'slider_show' ,'created_by_id', 'updated_by_id'
    ];

    //has one relation (for create this category user)
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }

    //has one relation (for update this category user)
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
