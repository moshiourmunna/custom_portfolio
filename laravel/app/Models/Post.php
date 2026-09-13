<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'slug', 'title', 'lead', 'body', 'cover', 'category', 'published_on',
        'status', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return ['published_on' => 'date'];
    }

    public function published(): bool
    {
        return $this->status === 'published';
    }
}
