<?php

namespace BiiiiiigMonster\Hasin\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Supplier extends Model
{
    use HasFactory;

    public function userHistory(): HasOneThrough
    {
        return $this->hasOneThrough(History::class, User::class);
    }
}
