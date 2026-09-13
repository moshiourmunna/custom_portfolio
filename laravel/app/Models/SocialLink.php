<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    protected $fillable = ['setting_id', 'label', 'url', 'sort'];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class);
    }
}
