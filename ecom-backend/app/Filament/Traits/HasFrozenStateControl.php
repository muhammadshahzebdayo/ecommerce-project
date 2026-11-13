<?php

namespace App\Filament\Traits;

trait HasFrozenStateControl
{
    public static function canCreate(): bool
    {
        $user = auth()->user();
        if ($user && $user->role === 'finance' && $user->is_frozen) {
            return false;
        }
        return true;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if ($user && $user->role === 'finance' && $user->is_frozen) {
            return false;
        }
        return true;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        if ($user && $user->role === 'finance' && $user->is_frozen) {
            return false;
        }
        return true;
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->role === 'finance' && $user->is_frozen) {
            return false;
        }
        return true;
    }
}
