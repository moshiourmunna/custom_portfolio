<?php

namespace App\Models;

use App\Support\Mill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'media_folder_id', 'path', 'disk', 'alt', 'caption', 'mime', 'width', 'height', 'bytes',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'media_folder_id');
    }

    public function url(): ?string
    {
        return Mill::url($this->path);
    }
}
