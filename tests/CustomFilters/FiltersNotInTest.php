<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\Tests;

use NycuCsit\SpatieLaravelQueryBuilderExtension\CustomFilters\FiltersNotIn;
use NycuCsit\SpatieLaravelQueryBuilderExtension\Tests\TestClasses\Models\TestModel;
use Spatie\QueryBuilder\AllowedFilter;

class FiltersNotInTest extends TestCase
{
    public function testNotIn()
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
                AllowedFilter::custom('category', new FiltersNotIn()),
            ])
            ->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->pluck('category')->contains('A'));
        $this->assertTrue($results->pluck('category')->contains('C'));
    }
}
