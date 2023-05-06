<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'file_path',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('troublelist/user/images' . $this->file_path);
    }
}
