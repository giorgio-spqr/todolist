<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $title
 * @property string $description
 * @property bool $is_completed
 */
class Task extends Model
{
    protected $fillable = [
        'title',
        'decription',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'bool',
    ];

    protected $attributes = [
        'is_completed' => false,
    ];
}