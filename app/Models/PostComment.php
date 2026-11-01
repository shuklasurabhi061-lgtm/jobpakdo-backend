<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];
    // Yeh relation add karein
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
