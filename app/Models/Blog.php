<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'excerpt',
        'content',
        'cover_image',
        'tag',
        'read_time',
        'status',
        'published_at',
    ];
}
