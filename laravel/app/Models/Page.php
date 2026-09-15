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

    public function sectionActive(string $id): bool
    {
        $raw = $this->field('section_status');
        if ($raw === null || $raw === '') {
            return true;
        }

        $status = json_decode($raw, true);
        if (! is_array($status) || ! array_key_exists($id, $status)) {
            return true;
        }

        return (bool) $status[$id];
    }

    public static function defaultHomeSections(): array
    {
        return [
            'hero', 'stats', 'products', 'insights', 'why', 'integration',
            'facilities', 'quality', 'markets', 'gallery', 'careers', 'cta',
        ];
    }

    public function sectionOrder(): array
    {
        $defaults = self::defaultHomeSections();
        $raw = $this->field('section_order');
        if ($raw === null || $raw === '') {
            return $defaults;
        }

        $order = json_decode($raw, true);
        if (! is_array($order)) {
            return $defaults;
        }

        $allowed = array_flip($defaults);
        $order = array_values(array_filter(
            $order,
            fn ($id) => is_string($id) && isset($allowed[$id])
        ));

        foreach ($defaults as $id) {
            if (! in_array($id, $order, true)) {
                $order[] = $id;
            }
        }

        return $order;
    }

    public function published(): bool
    {
        return $this->status === 'published';
    }
}
