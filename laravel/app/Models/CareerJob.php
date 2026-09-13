<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerJob extends Model
{
    protected $fillable = [
        'slug', 'title', 'location', 'department', 'employment_type',
        'summary', 'description', 'image', 'status', 'sort',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function open(): bool
    {
        return $this->status === 'open';
    }
}
