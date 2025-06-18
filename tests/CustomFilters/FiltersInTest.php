<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\Tests;

use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\FiltersIn;
use NycuCsit\SpatieLaravelQueryBuilderExtension\Tests\TestClasses\Models\TestModel;
use Spatie\QueryBuilder\AllowedFilter;

class FiltersInTest extends TestCase
{
    public function testIn()
    {
        TestModel::insert([
            ['name' => 'A','category' => 'A'],
            ['name' => 'B','category' => 'B'],
            ['name' => 'C','category' => 'C'],
        ]);

        $results = $this->createQueryFromFilterRequest([
            'category' => ['∈B'],
        ])
            ->allowedFilters([
                AllowedFilter::custom('category', new FiltersIn()),
            ])
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals('B', $results->first()->category);
    }
}
