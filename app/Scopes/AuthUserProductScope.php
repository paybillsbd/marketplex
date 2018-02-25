<?php

namespace MarketPlex\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class AuthUserProductScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Query\Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->with([ 'productbills' => function($query) {
            return $query->with([ 'product' => function($query) {
                return $query->where('user_id', Auth::user()->id);
            } ]);
        } ]);
    }
}                                                                                                                                                                                                                                                                                                                               