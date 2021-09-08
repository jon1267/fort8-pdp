<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'analytic_code', 'header_mobile', 'header_desktop', 'slider_show',
        'auction_comment_price', 'auction_register_price', 'auction_partner_price',
        'auction_product_text_ua', 'auction_product_text_ru',
        'created_by_id', 'updated_by_id'
    ];
}
