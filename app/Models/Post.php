<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
    public function likes()
   {
    return $this->hasMany(Like::class); // Or whatever your Like model name is
    }
    public function company()
    {
    return $this->belongsTo(CompanyProfile::class, 'company_id'); // Or Company::class based on your model name
    }
}
