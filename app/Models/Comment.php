<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ["user_id","post_id","comment_content"] ;

    public function posts():BelongsTo{
        return $this->belongsTo(Post::class,"post_id","id");
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class,"user_id","id");
    }
}
