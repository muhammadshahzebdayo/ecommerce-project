<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UdharRegistry extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'description',
        'amount',
        'due_date',
        'status',
        'settled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'settled_at' => 'date',
    ];
}
