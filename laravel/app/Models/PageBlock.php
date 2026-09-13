<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected $fillable = [
        'page_id', 'group', 'sort', 'title', 'text', 'value', 'suffix', 'meta',
        'year', 'role', 'initials', 'note', 'href', 'link_label', 'image',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
