<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\FiltersExact;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @template-implements \Spatie\QueryBuilder\Filters\Filter<TModelClass>
 */
class FiltersIn extends FiltersExact implements Filter
{
    protected array $relationConstraints = [];

    /** {@inheritdoc} */
    public function __invoke(Builder $query, $value, string $property)
    {
        // Handle relation properties, see: Spatie\QueryBuilder\Filters\ExactFilter
        if ($this->addRelationConstraint) {
            if ($this->isRelationProperty($query, $property)) {
                $this->withRelationConstraint($query, $value, $property);

                return;
            }
        }

        // Main logic
        if (str_starts_with($value[0], '∈')) {
            //∈ uses three bytes
            $value[0] = substr($value[0], 3);
            $query->whereIn($property, $value);
        } else {
            return;
        }
    }
}
