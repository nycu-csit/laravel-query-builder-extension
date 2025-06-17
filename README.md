# Spatie Laravel Query Builder Extension

This package currently maintains the custom filters required by NYCU CSIT for Spatie/laravel-query-builder. 

`Spatie\QueryBuilder\QueryBuilder` basic usage:

```php
$users = QueryBuilder::for(User::class)
    // this allow a filter on the `name` column
    ->allowedFilters('name')
    ->get();

// GET /users?filter[name]=John
// $users will contain only users with the name "John"
```

For more details, please refer to the [Spatie/laravel-query-builder documentation](https://spatie.be/docs/laravel-query-builder/v6/introduction).

## Spatie\QueryBuilder integration with NycuCsit\LaravelRestfulUtils

`Spatie\QueryBuilder\QueryBuilder` may be used in the `buildIndexQuery` method of a controller that use `NycuCsit\LaravelRestfulUtils\Controller\HasResourceActions` trait.

```php
use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\FiltersIn;

class ProductController extends Controllor
{
    use HasResourceActions;
    //...
    public function buildIndexQuery(): object
    {
        $query = QueryBuilder::for($this->query)
            ->allowedFilters([
                AllowedFilter::custom('category.name', new FiltersIn()),
                AllowedFilter::exact('name'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'creator',
            ])
            ->defaultSort('-created_at');

        return $query;
    }
    //...
}
```

## Development

The tests use sqlite in memory database, so you may need to install the sqlite driver for your PHP installation.

```bash
sudo apt install php-sqlite3
```

In some cases, two filters may interfere each others, do some tests if there are filters very likely to be used at the same time. [One example](https://laracasts.com/discuss/channels/eloquent/how-to-use-multiple-filters-in-spatie-query-builder)

## Custom Filters 

The filters is usually used under `allowedFilters` method of `Spatie\QueryBuilder\QueryBuilder`.

You can use the custom filters by the way provided by [Spatie/laravel-query-builder docs](https://spatie.be/docs/laravel-query-builder/v6/features/filtering#content-custom-filters)

### FiltersIn, FiltersNotIn

- Works on Relation models
- Can works on a column at the same time

```php 
$products = QueryBuilder::for(Product::class)
    ->allowedFilters([
        AllowedFilter::custom('category.name', new FiltersIn()),
    ])
    ->get();

// GET /products?filter[category]=∈electronics,books
// $products will contain only products that belong to the categories named "electronics" or "books"
```

```php 
$products = QueryBuilder::for(Product::class)
    ->allowedFilters([
        AllowedFilter::custom('category.name', new FiltersNotIn()),
    ])
    ->get();

// GET /products?filter[category]=∉electronics,books
// $products will contain only products that do not belong to the categories named "electronics" or "books"
```

`FiltersIn` and `FiltersNotIn` can be used on a column of at the same time.

```php
$products = QueryBuilder::for(Product::class)
    ->allowedFilters([
        AllowedFilter::custom('category.name', new FiltersIn()),
        AllowedFilter::custom('category.name', new FiltersNotIn()),
    ])
    ->get();
// GET /products?filter[category]=∈electronics,books
// $products will contain only products that belong to the categories named "electronics" or "books"
// GET /products?filter[category]=∉electronics,books
// $products will contain only products that do not belong to the categories named "electronics" or "books"
```
