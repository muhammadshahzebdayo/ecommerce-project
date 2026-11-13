<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'sku_prefix',
    ];

    protected static function booted()
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }

            if (empty($category->sku_prefix)) {
                $category->sku_prefix = self::generateSkuPrefix($category->name);
            }
        });
    }

    public static function generateSkuPrefix(string $name): string
    {
        $words = array_filter(explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $name)));

        if (count($words) === 0) {
            return 'CAT';
        }

        if (count($words) === 1) {
            return strtoupper(substr(reset($words), 0, 3));
        }

        if (count($words) === 2) {
            $first = array_shift($words);
            $second = array_shift($words);
            return strtoupper(substr($first, 0, 1) . substr($second, 0, 2));
        }

        $prefix = '';
        foreach (array_slice($words, 0, 3) as $word) {
            $prefix .= substr($word, 0, 1);
        }
        return strtoupper($prefix);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
