<?php


namespace BiiiiiigMonster\LaravelMixin\Database\Eloquent;

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
         * @param  \Illuminate\Database\Eloquent\Builder  $query
         * @param  \Illuminate\Database\Eloquent\Builder  $parentQuery
         * @param  array|mixed  $columns
         * @return \Illuminate\Database\Eloquent\Builder
         */
        return function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder{
            $belongsTo = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder{
                /** @var BelongsTo $this */
                $columns = $columns == ['*'] ? $query->qualifyColumn($this->ownerKey) : $columns;
                if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                return $query->select($columns);
            };
            $hasManyThrough = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder{
                /** @var HasManyThrough $this */
                $columns = $columns == ['*'] ? $this->getQualifiedFirstKeyName() : $columns;
                if ($parentQuery->getQuery()->from === $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                if ($parentQuery->getQuery()->from === $this->throughParent->getTable()) {
                    return $this->getRelationExistenceQueryForThroughSelfRelation($query, $parentQuery, $columns);
                }

                $this->performJoin($query);

                return $query->select($columns);
            };

            $relation = function (Builder $query, Builder $parentQuery, $columns = ['*']): Builder{
                /** @var Relation $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;
                return $query->select($columns);
            };
            $belongsToMany = function (Builder $query, Builder $parentQuery, $columns = ['*'])use($relation): Builder{
                /** @var BelongsToMany $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;// getExistenceCompareKey借用Exists 语法用到的id
                if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfJoin($query, $parentQuery, $columns);
                }

                $this->performJoin($query);

                return $relation($query, $parentQuery, $columns);
            };
            $morphToMany = function (Builder $query, Builder $parentQuery, $columns = ['*'])use($belongsToMany): Builder{
                /** @var MorphToMany $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;// getExistenceCompareKey借用Exists 语法用到的id
                return $belongsToMany($query, $parentQuery, $columns)->where(
                    $this->table.'.'.$this->morphType, $this->morphClass
                );
            };
            $hasOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*'])use($relation): Builder{
                /** @var HasOneOrMany $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;// getExistenceCompareKey借用Exists 语法用到的id
                if ($query->getQuery()->from == $parentQuery->getQuery()->from) {
                    return $this->getRelationExistenceQueryForSelfRelation($query, $parentQuery, $columns);
                }

                return $relation($query, $parentQuery, $columns);
            };
            $morphOneOrMany = function (Builder $query, Builder $parentQuery, $columns = ['*'])use($hasOneOrMany): Builder{
                /** @var MorphOneOrMany $this */
                $columns = $columns == ['*'] ? $this->getExistenceCompareKey() : $columns;// getExistenceCompareKey借用Exists 语法用到的id
                return $hasOneOrMany($query, $parentQuery, $columns)->where(
                    $query->qualifyColumn($this->getMorphType()), $this->morphClass
                );
            };

            /**
             * 上面的这些方法本应该是期望存在各个类中
             * $belongsTo：BelongsTo::getRelationInQuery
             * $hasManyThrough：HasManyThrough::getRelationInQuery
             * $relation：Relation::getRelationInQuery
             * ……
             * 本方法getRelationInQuery是对应getRelationExistenceQuery，都是对QueriesRelationships的实现底层支持
             * 但是框架的代码没法去写，虽然提供mixin混入，但是又因为各个关联类中存在继承，mixin无法支持针对继承类的方法单独混入
             * （有点绕，去实践一下就明白了），所以采用闭包的方式模拟方法继承（这种实现方式真的是灵光一闪）
             * 于是乎就定义好了方法之后，根据当前类名来进行动态函数调用
             * 这一套实现我愿称之为最强！！！
             */
            $relationName = (string)Str::of(get_class($this))->afterLast('\\')->camel();
            $function = ${$relationName}??$relation;//默认的继承关系为Relation基类
            return $function($query,$parentQuery,$columns);
        };
    }

    public function getRelationWhereInKey(): Closure
    {
        return function (): string{
            $belongsTo = function (): string{
                /** @var BelongsTo $this */
                return $this->getQualifiedForeignKeyName();
            };
            $hasManyThrough = function (): string{
                /** @var HasManyThrough $this */
                return $this->getQualifiedLocalKeyName();
            };

            $relation = function (): string{
                /** @var Relation $this */
                return $this->getQualifiedParentKeyName();
            };
            $belongsToMany = function ()use($relation): string{
                return $relation();
            };
            $morphToMany = function ()use($belongsToMany): string{
                return $belongsToMany();
            };
            $hasOneOrMany = function ()use($relation): string{
                return $relation();
            };
            $morphOneOrMany = function ()use($hasOneOrMany): string{
                return $hasOneOrMany();
            };

            $relationName = (string)Str::of(get_class($this))->afterLast('\\')->camel();
            $function = ${$relationName}??$relation;
            return $function();
        };
    }
}
