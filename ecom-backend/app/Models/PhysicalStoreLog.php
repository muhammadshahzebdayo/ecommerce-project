<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalStoreLog extends Model
{
    protected $fillable = [
        'physical_store_id',
        'type',
        'category',
        'amount',
        'date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(PhysicalStore::class, 'physical_store_id');
    }
}
