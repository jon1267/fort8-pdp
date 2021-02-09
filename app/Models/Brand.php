<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'created_by_id', 'updated_by_id'
    ];

    //has one relation (for create this aroma user)
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }

    //has one relation (for update this aroma user)
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by_id');
    }
}
