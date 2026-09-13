<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'eyebrow', 'lead', 'body', 'line', 'image', 'card_title',
        'card_lead', 'commitment_title', 'updated_on', 'status', 'meta_title', 'meta_description',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(PageField::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort');
    }

    public function field(string $key, ?string $default = null): ?string
    {
        $value = $this->fields->firstWhere('key', $key)?->value;

        return ($value === null || $value === '') ? $default : $value;
    }

    public function blocksFor(string $group)
    {
        return $this->blocks->where('group', $group)->values();
    }

    public function published(): bool
    {
        return $this->status === 'published';
    }
}
