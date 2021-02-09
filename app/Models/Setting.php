<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'analytic_code', 'header_mobile', 'header_desktop', 'slider_show', 'created_by_id', 'updated_by_id'
    ];
}
