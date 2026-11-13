<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'employee_type',
        'physical_store_id',
        'role',
        'salary',
        'status',
        'hired_at',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hired_at' => 'date',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(PhysicalStore::class, 'physical_store_id');
    }
}
