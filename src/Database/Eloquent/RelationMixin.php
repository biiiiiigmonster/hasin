<?php

namespace BiiiiiigMonster\Hasin\Database\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class RelationMixin
{
    public function getRelationInQuery(): Closure
    {
        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param \Illuminate\Database\Eloquent\Builder $parentQuery
         * @param array|mixed $columns
         * @return \Illuminate\Database\Eloquent\Builder
         */
        return function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
            // Abstract Relation
            $relation = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                /** @var Relation $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;
                return $query->select($columns);
            };
            $hasOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($relation): Builder {
                return $relation($query, $parentQuery, $columns);
            };
            $morphOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                /** @var MorphOneOrMany $this */
                return $hasOneOrMany($query, $parentQuery, $columns)->where(
                    $query->qualifyColumn($this->getMorphType()), $this->morphClass
                );
            };
            // Entity Relation
            // BelongsTo (extend Relation, overload)
            $belongsTo = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                /** @var BelongsTo $this */
                $columns = $columns == ['*'] ? $query->qualifyColumn($this->ownerKey) : $columns;

                return $query->select($columns);
            };
            // BelongsToMany (extend Relation, iteration)
            $belongsToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($relation): Builder {
                /** @var BelongsToMany $this */
                $this->performJoin($query);

                return $relation($query, $parentQuery, $columns);
            };
            // HasMany (extend HasOneOrMany, inherit)
            $hasMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                return $hasOneOrMany($query, $parentQuery, $columns);
            };
            // HasManyThrough (extend Relation, overload)
            $hasManyThrough = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                /** @var HasManyThrough $this */
                $columns = $columns == ['*'] ? $this->getQualifiedFirstKeyName() : $columns;
                if ($parentQuery->getQuery()->from === $this->throughParent->getTable()) {
                    $table = $this->throughParent->getTable() . ' as ' . $hash = $this->getRelationCountHash();

                    $query->join($table, $hash . '.' . $this->secondLocalKey, '=', $this->getQualifiedFarKeyName());

                    if ($this->throughParentSoftDeletes()) {
                        $query->whereNull($hash . '.' . $this->throughParent->getDeletedAtColumn());
                    }

                    return $query->select($columns);
                }

                $this->performJoin($query);

                return $query->select($columns);
            };
            // HasOne (extend HasOneOrMany, inherit)
            $hasOne = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                return $hasOneOrMany($query, $parentQuery, $columns);
            };
            // HasOneThrough (extend HasManyThrough, inherit)
            $hasOneThrough = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasManyThrough): Builder {
                return $hasManyThrough($query, $parentQuery, $columns);
            };
            // MorphMany (extend MorphOneOrMany, inherit)
            $morphMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($morphOneOrMany): Builder {
                return $morphOneOrMany($query, $parentQuery, $columns);
            };
            // MorphOne (extend MorphOneOrMany, inherit)
            $morphOne = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($morphOneOrMany): Builder {
                return $morphOneOrMany($query, $parentQuery, $columns);
            };
            // MorphTo (extend BelongsTo, inherit)
            $morphTo = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($belongsTo): Builder {
                return $belongsTo($query, $parentQuery, $columns);
            };
            // MorphToMany (extend BelongsToMany, iteration)
            $morphToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($belongsToMany): Builder {
                /** @var MorphToMany $this */
                return $belongsToMany($query, $parentQuery, $columns)->where(
                    $this->table . '.' . $this->morphType, $this->morphClass
                );
            };

            $relationName = (string)Str::of(get_class($this))->afterLast('\\')->camel();
            return ${$relationName}($query, $parentQuery, $columns);
        };
    }

    public function getRelationWhereInKey(): Closure
    {
        return function (): string {
            $relation = function (): string {
                /** @var Relation $this */
                return $this->getQualifiedParentKeyName();
            };
            $hasOneOrMany = function () use ($relation): string {
                return $relation();
            };
            $morphOneOrMany = function () use ($hasOneOrMany): string {
                return $hasOneOrMany();
            };

            $belongsTo = function (): string {
                /** @var BelongsTo $this */
                return $this->getQualifiedForeignKeyName();
            };
            $belongsToMany = function () use ($relation): string {
                return $relation();
            };
            $hasMany = function () use ($hasOneOrMany): string {
                return $hasOneOrMany();
            };
            $hasManyThrough = function (): string {
                /** @var HasManyThrough $this */
                return $this->getQualifiedLocalKeyName();
            };
            $hasOne = function () use ($hasOneOrMany): string {
                return $hasOneOrMany();
            };
            $hasOneThrough = function () use ($hasManyThrough): string {
                return $hasManyThrough();
            };
            $morphMany = function () use ($morphOneOrMany): string {
                return $morphOneOrMany();
            };
            $morphOne = function () use ($morphOneOrMany): string {
                return $morphOneOrMany();
            };
            $morphTo = function () use ($belongsTo): string {
                return $belongsTo();
            };
            $morphToMany = function () use ($belongsToMany): string {
                return $belongsToMany();
            };

            $relationName = (string)Str::of(get_class($this))->afterLast('\\')->camel();
            return ${$relationName}();
        };
    }
}
