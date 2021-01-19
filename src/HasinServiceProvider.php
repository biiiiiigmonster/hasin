<?php

namespace BiiiiiigMonster\Hasin;

use BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin;
use BiiiiiigMonster\Hasin\Database\Eloquent\RelationMixin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class HasinServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Eloquent\Builder mixin，provides hasin series implementation
        Builder::mixin(new BuilderMixin());
        // Eloquent\Relation mixin，support for the bottom layer of hasin
        Relation::mixin(new RelationMixin());
    }
}
