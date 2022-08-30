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
            $hasOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;

                return $query->select($columns);
            };
            $belongsTo = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                $columns = $columns == ['*'] ? $query->qualifyColumn($this->ownerKey) : $columns;

                return $query->select($columns);
            };
            $belongsToMany = function (Builder $query, Builder $parentQuery, $columns = ['*']) use ($hasOneOrMany): Builder {
                $this->performJoin($query);

                return $hasOneOrMany($query, $parentQuery, $columns);
            };
            $hasManyThrough = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder {
                $columns = $columns == ['*'] ? $this->getQualifiedFirstKeyName() : $columns;
                if ($parentQuery->getQuery()->from === $this->throughParent->getTable()) {
                    $table = $this->throughParent->getTable().' as '.$hash = $this->getRelationCountHash();

                    $query->join($table, $hash.'.'.$this->secondLocalKey, '=', $this->getQualifiedFarKeyName());

                    if ($this->throughParentSoftDeletes()) {
                        $query->whereNull($hash.'.'.$this->throughParent->getDeletedAtColumn());
                    }

                    return $query->select($columns);
                }

                $this->performJoin($query);

                return $query->select($columns);
            };

            $builder = match ($this::class) {
                HasOne::class, HasMany::class, => $hasOneOrMany($query, $parentQuery, $columns),
                BelongsTo::class, MorphTo::class => $belongsTo($query, $parentQuery, $columns),
                MorphOne::class, MorphMany::class => $hasOneOrMany($query, $parentQuery, $columns)->where(
                    $query->qualifyColumn($this->getMorphType()),
                    $this->morphClass
                ),
                BelongsToMany::class => $belongsToMany($query, $parentQuery, $columns),
                MorphToMany::class => $belongsToMany($query, $parentQuery, $columns)->where(
                    $this->table.'.'.$this->morphType,
                    $this->morphClass
                ),
                HasOneThrough::class, HasManyThrough::class => $hasManyThrough($query, $parentQuery, $columns),
                default => throw new LogicException(
                    sprintf('%s must be a relationship instance.', $this::class)
                )
            };

            return $builder->distinct();
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
