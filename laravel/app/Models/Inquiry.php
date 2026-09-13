<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'country', 'interest', 'message',
        'status', 'note', 'image', 'attachment_path', 'received_on',
    ];

    protected function casts(): array
    {
        return ['received_on' => 'date'];
    }
}
