<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title'];

    protected $attributes = [
        'completed' => false,
    ];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }
}
