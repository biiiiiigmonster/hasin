<?php

namespace BiiiiiigMonster\Hasin\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    public function posts(): MorphToMany
    {
        return $this->morphToMany(Post::class, 'taggable')->using(Taggable::class);
    }

    public function videos(): MorphToMany
    {
        return $this->morphToMany(Video::class, 'taggable')->using(Taggable::class);
    }
}
