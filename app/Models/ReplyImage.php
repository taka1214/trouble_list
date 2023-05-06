<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply_id',
        'file_path',
    ];

    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }
}
