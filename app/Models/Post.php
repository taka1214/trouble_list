<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'body',
        'owner_id',
        'user_id',
        'image_file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
