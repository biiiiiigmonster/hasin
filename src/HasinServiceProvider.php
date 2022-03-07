<?php

namespace BiiiiiigMonster\Hasin;

use BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin;
use BiiiiiigMonster\Hasin\Database\Eloquent\RelationMixin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use ReflectionException;
use ReflectionClass;
use ReflectionMethod;

class HasinServiceProvider extends ServiceProvider
{
    /**
     * @throws ReflectionException
     */
    public function register()
    {
        // Eloquent\Builder mixin, provides hasin series implementation.
        /**
         * Why not use 'Builder::mixin(new BuilderMixin())'?
         * Compatible laravel5.5!
         */
        $mixin = new BuilderMixin();
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );
        foreach ($methods as $method) {
            $method->setAccessible(true);
            Builder::macro($method->name, $method->invoke($mixin));
        }
        // Eloquent\Relation mixin, support for the bottom layer of hasin.
        Relation::mixin(new RelationMixin());
    }
}
