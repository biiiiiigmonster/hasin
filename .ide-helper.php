<?php

namespace Illuminate\Database\Query {
    /**
     *
     *
     */
    class Builder {
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::hasIn()
         * @param mixed $relation
         * @param mixed $operator
         * @param mixed $count
         * @param mixed $boolean
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function hasIn($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::hasIn($relation, $operator, $count, $boolean, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orHasIn()
         * @param mixed $relation
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orHasIn($relation, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::orHasIn($relation, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::doesntHaveIn()
         * @param mixed $relation
         * @param mixed $boolean
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function doesntHaveIn($relation, $boolean = 'and', $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::doesntHaveIn($relation, $boolean, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orDoesntHaveIn()
         * @param mixed $relation
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orDoesntHaveIn($relation)
        {
            return \Illuminate\Database\Eloquent\Builder::orDoesntHaveIn($relation);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::whereHasIn()
         * @param mixed $relation
         * @param \Closure|null $callback
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function whereHasIn($relation, $callback = null, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::whereHasIn($relation, $callback, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orWhereHasIn()
         * @param mixed $relation
         * @param \Closure|null $callback
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orWhereHasIn($relation, $callback = null, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::orWhereHasIn($relation, $callback, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::whereDoesntHaveIn()
         * @param mixed $relation
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function whereDoesntHaveIn($relation, $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::whereDoesntHaveIn($relation, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orWhereDoesntHaveIn()
         * @param mixed $relation
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orWhereDoesntHaveIn($relation, $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::orWhereDoesntHaveIn($relation, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::hasMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param mixed $operator
         * @param mixed $count
         * @param mixed $boolean
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function hasMorphIn($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::hasMorphIn($relation, $types, $operator, $count, $boolean, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orHasMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orHasMorphIn($relation, $types, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::orHasMorphIn($relation, $types, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::doesntHaveMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param mixed $boolean
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function doesntHaveMorphIn($relation, $types, $boolean = 'and', $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::doesntHaveMorphIn($relation, $types, $boolean, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orDoesntHaveMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orDoesntHaveMorphIn($relation, $types)
        {
            return \Illuminate\Database\Eloquent\Builder::orDoesntHaveMorphIn($relation, $types);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::whereHasMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param \Closure|null $callback
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function whereHasMorphIn($relation, $types, $callback = null, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::whereHasMorphIn($relation, $types, $callback, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orWhereHasMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param \Closure|null $callback
         * @param mixed $operator
         * @param mixed $count
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orWhereHasMorphIn($relation, $types, $callback = null, $operator = '>=', $count = 1)
        {
            return \Illuminate\Database\Eloquent\Builder::orWhereHasMorphIn($relation, $types, $callback, $operator, $count);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::whereDoesntHaveMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function whereDoesntHaveMorphIn($relation, $types, $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::whereDoesntHaveMorphIn($relation, $types, $callback);
        }
        /**
         *
         *
         * @see \BiiiiiigMonster\Hasin\Database\Eloquent\BuilderMixin::orWhereDoesntHaveMorphIn()
         * @param mixed $relation
         * @param mixed $types
         * @param \Closure|null $callback
         * @return \Illuminate\Database\Eloquent\Builder
         * @static
         */
        public static function orWhereDoesntHaveMorphIn($relation, $types, $callback = null)
        {
            return \Illuminate\Database\Eloquent\Builder::orWhereDoesntHaveMorphIn($relation, $types, $callback);
        }
    }
}
