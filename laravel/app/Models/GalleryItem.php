<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    protected $fillable = ['media_id', 'album', 'image', 'alt', 'caption', 'sort'];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
