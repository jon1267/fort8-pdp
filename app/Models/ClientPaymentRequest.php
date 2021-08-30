<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'client_payment_requests';

    protected $fillable = ['client_id', 'sum', 'comment', 'card', 'paid'];
}
