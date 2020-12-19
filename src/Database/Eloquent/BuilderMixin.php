<?php
namespace BiiiiiigMonster\LaravelMixin\Database\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class BuilderMixin
{
//    public function whereBetweenHas(): Closure{}
//    public function orWhereBetweenHas(): Closure{}
//    public function whereNotBetweenHas(): Closure{}
//    public function orWhereNotBetweenHas(): Closure{}
//
//    public function whereInHas(): Closure{}
//    public function orWhereInHas(): Closure{}
//    public function whereNotInHas(): Closure{}
//    public function orWhereNotInHas(): Closure{}
//
//    public function whereNullHas(): Closure{}
//    public function orWhereNullHas(): Closure{}
//    public function whereNotNullHas(): Closure{}
//    public function orWhereNotNullHas(): Closure{}

    /**
     * 仿造框架提供where in实现，全部流程与where exists一致
     * @return Closure
     */
    public function hasIn(): Closure
    {
        return function ($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): Builder{
            /** @var Builder $this */
            if (is_string($relation)) {
                if (strpos($relation, '.') !== false) {
                    return $this->hasInNested($relation, $operator, $count, $boolean, $callback);
                }

                $relation = $this->getRelationWithoutConstraints($relation);
            }

            if ($relation instanceof MorphTo) {
                throw new RuntimeException('Please use whereHasMorph() for MorphTo relationships.');
            }

            // If we only need to check for the existence of the relation, then we can optimize
            // the subquery to only run a "where in" clause instead of this full "count"
            // clause. This will make these queries run much faster compared with a count.
            $method = $this->canUseInForExistenceCheck($operator, $count)
                ? 'getRelationInQuery'
                : 'getRelationExistenceCountQuery';

            $hasInQuery = $relation->{$method}(
                $relation->getRelated()->newQueryWithoutRelationships(), $this
            );

            // Next we will call any given callback as an "anonymous" scope so they can get the
            // proper logical grouping of the where clauses if needed by this Eloquent query
            // builder. Then, we will be ready to finalize and return this query instance.
            if ($callback) {
                $hasInQuery->callScope($callback);
            }

            return $this->addHasInWhere(
                $hasInQuery, $relation, $operator, $count, $boolean
            );
        };
    }
    public function orHasIn(): Closure
    {
        return function ($relation, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'or');
        };
    }
    public function doesntHaveIn(): Closure
    {
        return function ($relation, $boolean = 'and', Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->hasIn($relation, '<', 1, $boolean, $callback);
        };
    }
    public function orDoesntHaveIn(): Closure
    {
        return function ($relation): Builder{
            /** @var Builder $this */
            return $this->doesntHaveIn($relation,'or');
        };
    }
    public function whereHasIn(): Closure
    {
        return function ($relation, Closure $callback = null, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'and', $callback);
        };
    }
    public function orWhereHasIn(): Closure{
        return function ($relation, Closure $callback = null, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasIn($relation, $operator, $count, 'or', $callback);
        };
    }
    public function whereDoesntHaveIn(): Closure
    {
        return function ($relation, Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->doesntHaveIn($relation, 'and', $callback);
        };
    }
    public function orWhereDoesntHaveIn(): Closure
    {
        return function ($relation, Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->doesntHaveIn($relation, 'or', $callback);
        };
    }

    public function hasMorphIn(): Closure
    {
        return function (): Builder{
            /** @var Builder $this */
            // to do
            return $this;
        };
    }
    public function orHasMorphIn(): Closure
    {
        return function ($relation, $types, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'or');
        };
    }
    public function doesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, $boolean = 'and', Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, '<', 1, $boolean, $callback);
        };
    }
    public function orDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types): Builder{
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'or');
        };
    }
    public function whereHasMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'and', $callback);
        };
    }
    public function orWhereHasMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): Builder{
            /** @var Builder $this */
            return $this->hasMorphIn($relation, $types, $operator, $count, 'or', $callback);
        };
    }
    public function whereDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'and', $callback);
        };
    }
    public function orWhereDoesntHaveMorphIn(): Closure
    {
        return function ($relation, $types, Closure $callback = null): Builder{
            /** @var Builder $this */
            return $this->doesntHaveMorphIn($relation, $types, 'or', $callback);
        };
    }

    protected function hasInNested(): Closure
    {
        return function ($relations, $operator = '>=', $count = 1, $boolean = 'and', $callback = null): Builder{
            /** @var Builder $this */
            $relations = explode('.', $relations);

            $doesntHave = $operator === '<' && $count === 1;

            if ($doesntHave) {
                $operator = '>=';
                $count = 1;
            }

            $closure = function ($q) use (&$closure, &$relations, $operator, $count, $callback) {
                // In order to nest "has", we need to add count relation constraints on the
                // callback Closure. We'll do this by simply passing the Closure its own
                // reference to itself so it calls itself recursively on each segment.
                count($relations) > 1
                    ? $q->whereHasIn(array_shift($relations), $closure)
                    : $q->hasIn(array_shift($relations), $operator, $count, 'and', $callback);
            };

            return $this->hasIn(array_shift($relations), $doesntHave ? '<' : '>=', 1, $boolean, $closure);
        };
    }
    protected function canUseInForExistenceCheck(): Closure
    {
        /**
         * @param  $operator
         * @param  $count
         * @return bool
         */
        return function ($operator, $count): bool{
            return ($operator === '>=' || $operator === '<') && $count === 1;
        };
    }
    protected function addHasInWhere(): Closure
    {
        return function (Builder $hasInQuery, Relation $relation, $operator, $count, $boolean): Builder{
            /** @var Builder $this */
            $hasInQuery->mergeConstraintsFrom($relation->getQuery());

            return $this->canUseInForExistenceCheck($operator, $count)
                ? $this->whereIn($relation->getRelationWhereInKey(), $hasInQuery->toBase(), $boolean, $operator === '<' && $count === 1)
                : $this->addWhereCountQuery($hasInQuery->toBase(), $operator, $count, $boolean);
        };
    }

//    public function withCount(): Closure{}
//    public function withMax(): Closure{}
//    public function withMin(): Closure{}
//    public function withSum(): Closure{}
//    public function withAvg(): Closure{}
}
