<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = ['slug', 'name', 'text', 'href', 'image', 'sort'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
