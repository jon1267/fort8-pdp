<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'client_payment_requests';

    protected $fillable = ['client_id', 'sum', 'comment', 'card', 'paid'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
