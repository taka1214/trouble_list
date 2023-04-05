<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'post_id',
        'user_id',
        'owner_id',
    ];

    public function posts()
    {
        return $this->BelongsTo(Post::class);
    }
}
