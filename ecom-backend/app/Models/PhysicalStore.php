<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalStore extends Model
{
    protected $fillable = [
        'name',
        'address',
        'monthly_rent',
        'status',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PhysicalStoreLog::class);
    }
}
