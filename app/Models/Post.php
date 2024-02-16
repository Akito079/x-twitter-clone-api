<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements ReactableInterface
{
    use HasFactory,Reactable;
    protected $fillable = ['user_id','content','media'] ;

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function comments():HasMany{
        return $this->hasMany(Comment::class,"post_id","id");
    }

    public function hashtags():BelongsToMany{
        return $this->belongsToMany(Hashtag::class,'post_hashtags');
    }
}
