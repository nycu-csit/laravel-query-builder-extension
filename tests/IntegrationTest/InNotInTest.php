<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\Tests;

use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\FiltersIn;
use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\FiltersNotIn;
use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\InNotInFilter;
use NycuCsit\SpatieLaravelQueryBuilderExtension\Tests\TestClasses\Models\TestModel;
use Spatie\QueryBuilder\AllowedFilter;

class InNotInTest extends TestCase
{
    public function testFiltersInFiltersNotInCanWorkTogether()
    {
        TestModel::insert([
            ['name' => 'A','category' => 'A'],
            ['name' => 'B','category' => 'B'],
            ['name' => 'C','category' => 'C'],
        ]);

        $results = $this->createQueryFromFilterRequest([
            'category' => ['∉B'],
        ])
            ->allowedFilters([
                AllowedFilter::custom('category', new FiltersIn()),
                AllowedFilter::custom('category', new FiltersNotIn()),
            ])
            ->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->pluck('category')->contains('A'));
        $this->assertTrue($results->pluck('category')->contains('C'));

        $results = $this->createQueryFromFilterRequest([
            'category' => ['∈B'],
        ])
            ->allowedFilters([
                AllowedFilter::custom('category', new FiltersIn()),
                AllowedFilter::custom('category', new FiltersNotIn()),
            ])
            ->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->pluck('category')->contains('B'));
    }
}
