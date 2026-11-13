<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPartner extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function shippings(): HasMany
    {
        return $this->hasMany(DeliveryShipping::class);
    }
}
