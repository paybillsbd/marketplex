<?php

namespace MarketPlex\Traits;

trait DataIntegrityScope
{
    // remove those collection items those are crossed/ unchecked/ deleted (X) from view
    public function scopeRemoveCrossed($query, array $inputs, array $idsExists)
    {
        return (! $query->get()->IsEmpty() && empty($inputs)) ?
                $query->delete() : $query->whereNotIn('id', $idsExists)->delete();
    }
}