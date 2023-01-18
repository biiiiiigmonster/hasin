<?php

namespace BiiiiiigMonster\Hasin\Database\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class BuilderMixin
{
    /**
     * Add a relationship count / whereIn condition to the query.
     *
     * @return Closure
     */
    public function hasIn(): Closure
    {
        return function ($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): Builder {
            /** @var Builder $this */
            if (is_string($relation)) {
                if (str_contains($relation, '.')) {
                    return $this->hasInNested($relation, $operator, $count, $boolean, $callback);
                }

                $relation = $this->getRelationWithoutConstraints($relation);
            }

            if ($relation instanceof MorphTo) {
                return $this->hasMorphIn($relation, ['*'], $operator, $count, $boolean, $callback);
            }

            // If we only need to check for the existence of the relation, then we can optimize
            // the subquery to only run a "where in" clause instead of this full "count"
            // clause. This will make these queries run much faster compared with a count.
            $method = $this->canUseExistsForExistenceCheck($operator, $count)
                            ? 'getRelationExistenceInQuery'
                            : 'getRelationExistenceCountQuery';

            $hasInQuery = $relation->{$method}(
                $relation->getRelated()->newQueryWithoutRelationships(),
                $this
            );

            // Next we will call any given callback as an "anonymous" scope so they can get the
            // proper logical grouping of the where clauses if needed by this Eloquent query
            // builder. Then, we will be ready to finalize and return this query instance.
            if ($callback) {
                $hasInQuery->callScope($callback);
            }

            return $this->addHasInWhere(
                $hasInQuery,
                $relation,
                $operator,
                $count,
                $boolean
            );
        };
    }

    /**
     * Add nested relationship count / whereIn conditions to the query.
     *
     * Sets up recursive call to whereHas until we finish the nested relation.
     *
     * @return Closure
     */
    protected function hasInNested(): Closure
    {
        return function ($relations, $operator = '>=', $count = 1, $boolean = 'and', $callback = null): Builder {
            /** @var Builder $this */
            $relations = explode('.', $relations);

            $doesntHave = $operator === '<' && $count === 1;

            if ($doesntHave) {
                $operator = '>=';
                $count = 1;
            }

            $closure = function ($q) use (&$closure, &$relations, $operator, $count, $callback) {
                // In order to nest "hasIn", we need to add count relation constraints on the
                // callback Closure. We'll do this by simply passing the Closure its own
                // reference to itself so it calls itself recursively on each segment.
                count($relations) > 1
                    ? $q->whereHasIn(array_shift($relations), $closure)
                    : $q->hasIn(array_shift($relations), $operator, $count, 'and', $callback);
            };

            return $this->hasIn(array_shift($relations), $doesntHave ? '<' : '>=', 1, $boolean, $closure);
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with an "or".
     *
     * @return Closure
     */
    public function orHasIn(): Closure
    {
        return function ($relation, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'or');
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query.
     *
     * @return Closure
     */
    public function doesntHaveIn(): Closure
    {
        return function ($relation, $boolean = 'and', Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->hasIn($relation, '<', 1, $boolean, $callback);
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with an "or".
     *
     * @return Closure
     */
    public function orDoesntHaveIn(): Closure
    {
        return function ($relation): Builder {
            /** @var Builder $this */
            return $this->doesntHaveIn($relation, 'or');
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with where clauses.
     *
     * @return Closure
     */
    public function whereHasIn(): Closure
    {
        return function ($relation, Closure $callback = null, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'and', $callback);
        };
    }

    /**
     * Add a relationship count / exists condition to the query with whereIn clauses.
     *
     * Also load the relationship with same condition.
     *
     * @return Closure
     */
    public function withWhereHasIn(): Closure
    {
        return function ($relation, Closure $callback = null, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->whereHasIn(Str::before($relation, ':'), $callback, $operator, $count)
                ->with($callback ? [$relation => fn ($query) => $callback($query)] : $relation);
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with where clauses and an "or".
     *
     * @return Closure
     */
    public function orWhereHasIn(): Closure
    {
        return function ($relation, Closure $callback = null, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'or', $callback);
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with where clauses.
     *
     * @return Closure
     */
    public function whereDoesntHaveIn(): Closure
    {
        return function ($relation, Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->doesntHaveIn($relation, 'and', $callback);
        };
    }

    /**
     * Add a relationship count / whereIn condition to the query with where clauses and an "or".
     *
     * @return Closure
     */
    public function orWhereDoesntHaveIn(): Closure
    {
        return function ($relation, Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->doesntHaveIn($relation, 'or', $callback);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query.
     *
     * @return Closure
     */
    public function hasMorphIn(): Closure
    {
        return function ($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): Builder {
            /** @var Builder $this */
            if (is_string($relation)) {
                $relation = $this->getRelationWithoutConstraints($relation);
            }

            $types = (array) $types;

            if ($types === ['*']) {
                $types = $this->model->newModelQuery()->distinct()->pluck($relation->getMorphType())->filter()->all();
            }

            foreach ($types as &$type) {
                $type = Relation::getMorphedModel($type) ?? $type;
            }

            return $this->where(function ($query) use ($relation, $callback, $operator, $count, $types) {
                foreach ($types as $type) {
                    $query->orWhere(function ($query) use ($relation, $callback, $operator, $count, $type) {
                        $belongsTo = $this->getBelongsToRelation($relation, $type);

                        if ($callback) {
                            $callback = function ($query) use ($callback, $type) {
                                return $callback($query, $type);
                            };
                        }

                        $query->where($this->qualifyColumn($relation->getMorphType()), '=', (new $type())->getMorphClass())
                            ->whereHasIn($belongsTo, $callback, $operator, $count);
                    });
                }
            }, null, null, $boolean);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with an "or".
     *
     * @return Closure
     */
    public function orHasMorphIn(): Closure
    {
        return function ($relation, $types, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'or');
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query.
     *
     * @return Closure
     */
    public function doesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, $boolean = 'and', Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, '<', 1, $boolean, $callback);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with an "or".
     *
     * @return Closure
     */
    public function orDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types): Builder {
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'or');
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with where clauses.
     *
     * @return Closure
     */
    public function whereHasMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'and', $callback);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with where clauses and an "or".
     *
     * @return Closure
     */
    public function orWhereHasMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): Builder {
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'or', $callback);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with where clauses.
     *
     * @return Closure
     */
    public function whereDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'and', $callback);
        };
    }

    /**
     * Add a polymorphic relationship count / whereIn condition to the query with where clauses and an "or".
     *
     * @return Closure
     */
    public function orWhereDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null): Builder {
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'or', $callback);
        };
    }

    /**
     * Add a basic where clause to a relationship query.
     *
     * @return Closure
     */
    public function whereRelationIn(): Closure
    {
        return function ($relation, $column, $operator = null, $value = null): Builder {
            return $this->whereHasIn($relation, function ($query) use ($column, $operator, $value) {
                if ($column instanceof Closure) {
                    $column($query);
                } else {
                    $query->where($column, $operator, $value);
                }
            });
        };
    }

    /**
     * Add an "or where" clause to a relationship query.
     *
     * @return Closure
     */
    public function orWhereRelationIn(): Closure
    {
        return function ($relation, $column, $operator = null, $value = null): Builder {
            return $this->orWhereHasIn($relation, function ($query) use ($column, $operator, $value) {
                if ($column instanceof Closure) {
                    $column($query);
                } else {
                    $query->where($column, $operator, $value);
                }
            });
        };
    }

    /**
     * Add a polymorphic relationship condition to the query with a where clause.
     *
     * @return Closure
     */
    public function whereMorphRelationIn(): Closure
    {
        return function ($relation, $types, $column, $operator = null, $value = null): Builder {
            return $this->whereHasMorphIn($relation, $types, function ($query) use ($column, $operator, $value) {
                $query->where($column, $operator, $value);
            });
        };
    }

    /**
     * Add a polymorphic relationship condition to the query with an "or where" clause.
     *
     * @return Closure
     */
    public function orWhereMorphRelationIn(): Closure
    {
        return function ($relation, $types, $column, $operator = null, $value = null): Builder {
            return $this->orWhereHasMorphIn($relation, $types, function ($query) use ($column, $operator, $value) {
                $query->where($column, $operator, $value);
            });
        };
    }

    /**
     * Add the "hasin" condition whereIn clause to the query.
     *
     * @return Closure
     */
    protected function addHasInWhere(): Closure
    {
        return function (Builder $hasInQuery, Relation $relation, $operator, $count, $boolean): Builder {
            /** @var Builder $this */
            $hasInQuery->mergeConstraintsFrom($relation->getQuery());

            return $this->canUseExistsForExistenceCheck($operator, $count)
                ? $this->whereIn($relation->getRelationWhereInKey(), $hasInQuery->toBase(), $boolean, $operator === '<' && $count === 1)
                : $this->addWhereCountQuery($hasInQuery->toBase(), $operator, $count, $boolean);
        };
    }
}
