<?php

namespace BiiiiiigMonster\Hasin\Database\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use LogicException;

class RelationMixin
{
    public function getRelationExistenceInQuery(): Closure
    {
        return function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
            $relation = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                return $query->select($columns)->whereColumn(
                    $this->getQualifiedParentKeyName(), '=', $this->getExistenceCompareKey()
                );
            };
            $belongsTo = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                return $query->select($columns)->whereColumn(
                    $this->getQualifiedForeignKeyName(), '=', $query->qualifyColumn($this->ownerKey)
                );
            };
            $belongsToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($relation): Builder {
                if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfJoin($query, $parentQuery, $columns);
                }

                $this->performJoin($query);

                return $relation($query, $parentQuery, $columns);
            };
            $hasOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($relation): Builder {
                if ($query->getQuery()->from == $parentQuery->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                return $relation($query, $parentQuery, $columns);
            };
            $hasOne = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                if ($this->isOneOfMany()) {
                    $this->mergeOneOfManyJoinsTo($query);
                }

                return $hasOneOrMany($query, $parentQuery, $columns);
            };
            $morphOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                return $hasOneOrMany($query, $parentQuery, $columns)->where(
                    $query->qualifyColumn($this->getMorphType()), $this->morphClass
                );
            };
            $morphOne = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($morphOneOrMany): Builder {
                if ($this->isOneOfMany()) {
                    $this->mergeOneOfManyJoinsTo($query);
                }

                return $morphOneOrMany($query, $parentQuery, $columns);
            };
            $belongsToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($relation): Builder {
                if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfJoin($query, $parentQuery, $columns);
                }

                $this->performJoin($query);

                return $relation($query, $parentQuery, $columns);
            };
            $morphToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($belongsToMany): Builder {
                return $belongsToMany($query, $parentQuery, $columns)->where(
                    $this->qualifyPivotColumn($this->morphType), $this->morphClass
                );
            };
            $hasManyThrough = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                if ($parentQuery->getQuery()->from === $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                if ($parentQuery->getQuery()->from === $this->throughParent->getTable()) {
                    return $this->getRelationExistenceQueryForThroughSelfRelation($query, $parentQuery, $columns);
                }

                $this->performJoin($query);

                return $query->select($columns)->whereColumn(
                    $this->getQualifiedLocalKeyName(), '=', $this->getQualifiedFirstKeyName()
                );
            };

            return match ($this::class) {
                MorphMany::class => $morphOneOrMany($query, $parentQuery, $columns),
                BelongsTo::class, MorphTo::class => $belongsTo($query, $parentQuery, $columns),
                HasMany::class, => $hasOneOrMany($query, $parentQuery, $columns),
                HasOne::class => $hasOne($query, $parentQuery, $columns),
                MorphOne::class => $morphOne($query, $parentQuery, $columns),
                BelongsToMany::class => $belongsToMany($query, $parentQuery, $columns),
                MorphToMany::class => $morphToMany($query, $parentQuery, $columns),
                HasOneThrough::class, HasManyThrough::class => $hasManyThrough($query, $parentQuery, $columns),
                default => throw new LogicException(
                    sprintf('%s must be a relationship instance.', $this::class)
                )
            };
        };
    }

    public function getRelationWhereInKey(): Closure
    {
        return fn (): string => match ($this::class) {
            BelongsTo::class, MorphTo::class => $this->getQualifiedForeignKeyName(),
            HasOne::class, HasMany::class, BelongsToMany::class,
            MorphMany::class, MorphOne::class, MorphToMany::class => $this->getQualifiedParentKeyName(),
            HasOneThrough::class, HasManyThrough::class => $this->getQualifiedLocalKeyName(),
            default => throw new LogicException(
                sprintf('%s must be a relationship instance.', $this::class)
            )
        };
    }
}
