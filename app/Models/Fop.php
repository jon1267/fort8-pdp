<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fop extends Model
{
    use HasFactory;

    protected $guarded = [];

    //has one relation (for create this fop user)
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }

    //has one relation (for update this fop user)
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by_id');
    }
}
