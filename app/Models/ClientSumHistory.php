<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSumHistory extends Model
{
    use HasFactory;

    protected $table = 'client_sum_histories';

    protected $fillable = ['client_id', 'note', 'amount'];
}
