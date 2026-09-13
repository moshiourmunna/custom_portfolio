<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingContact extends Model
{
    protected $fillable = ['setting_id', 'kind', 'value', 'sort'];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class);
    }
}
