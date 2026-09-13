<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'slug', 'title', 'summary', 'description', 'count', 'weave',
        'finish', 'construction', 'gsm', 'filter_count', 'status', 'image', 'meta_title',
        'meta_description', 'updated_on', 'sort',
    ];

    protected function casts(): array
    {
        return ['updated_on' => 'date'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort');
    }

    public function published(): bool
    {
        return $this->status === 'published';
    }
}
