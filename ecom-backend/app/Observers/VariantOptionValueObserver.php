<?php

namespace App\Observers;

use App\Models\VariantOptionValue;

class VariantOptionValueObserver
{
    /**
     * Handle the VariantOptionValue "saved" event.
     */
    public function saved(VariantOptionValue $variantOptionValue): void
    {
        $variantOptionValue->variant->generateSku();
    }

    /**
     * Handle the VariantOptionValue "deleted" event.
     */
    public function deleted(VariantOptionValue $variantOptionValue): void
    {
        $variantOptionValue->variant->generateSku();
    }
}
