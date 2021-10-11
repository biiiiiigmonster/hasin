<?php

namespace Illuminate\Database\Eloquent
{
    use Closure;

    if (false) {
        class Builder
        {
            /**
             * Add a relationship count / whereIn condition to the query.
             *
             * @param \Illuminate\Database\Eloquent\Relations\Relation|string $relation
             * @param string                                                  $operator
             * @param int                                                     $count
             * @param string                                                  $boolean
             * @param \Closure|null                                           $callback
             *
             * @throws \RuntimeException
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function hasIn($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with an "or".
             *
             * @param string $relation
             * @param string $operator
             * @param int    $count
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orHasIn($relation, $operator = '>=', $count = 1)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query.
             *
             * @param string        $relation
             * @param string        $boolean
             * @param \Closure|null $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function doesntHaveIn($relation, $boolean = 'and', Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with an "or".
             *
             * @return Closure
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orDoesntHaveIn()
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with where clauses.
             *
             * @param string        $relation
             * @param \Closure|null $callback
             * @param string        $operator
             * @param int           $count
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function whereHasIn($relation, Closure $callback = null, $operator = '>=', $count = 1)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with where clauses and an "or".
             *
             * @param string        $relation
             * @param \Closure|null $callback
             * @param string        $operator
             * @param int           $count
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orWhereHasIn($relation, Closure $callback = null, $operator = '>=', $count = 1)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with where clauses.
             *
             * @param string        $relation
             * @param \Closure|null $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function whereDoesntHaveIn($relation, Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a relationship count / whereIn condition to the query with where clauses and an "or".
             *
             * @param string        $relation
             * @param \Closure|null $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orWhereDoesntHaveIn($relation, Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query.
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param string                                                 $operator
             * @param int                                                    $count
             * @param string                                                 $boolean
             * @param \Closure|null                                          $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function hasMorphIn($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with an "or".
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param string                                                 $operator
             * @param int                                                    $count
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orHasMorphIn($relation, $types, $operator = '>=', $count = 1)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query.
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param string                                                 $boolean
             * @param \Closure|null                                          $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function doesntHaveMorphIn($relation, $types, $boolean = 'and', Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with an "or".
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orDoesntHaveMorphIn($relation, $types)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with where clauses.
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             *
             * @return Closure
             */
            public function whereHasMorphIn()
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with where clauses.
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param \Closure|null                                          $callback
             * @param string                                                 $operator
             * @param int                                                    $count
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orWhereHasMorphIn($relation, $types, Closure $callback = null, $operator = '>=', $count = 1)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with where clauses.
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param \Closure|null                                          $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function whereDoesntHaveMorphIn($relation, $types, Closure $callback = null)
            {
                return $this;
            }

            /**
             * Add a polymorphic relationship count / whereIn condition to the query with where clauses and an "or".
             *
             * @param \Illuminate\Database\Eloquent\Relations\MorphTo|string $relation
             * @param string|array                                           $types
             * @param \Closure|null                                          $callback
             *
             * @return \Illuminate\Database\Eloquent\Builder|static
             *
             * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin
             */
            public function orWhereDoesntHaveMorphIn($relation, $types, Closure $callback = null)
            {
                return $this;
            }
        }
    }
}
