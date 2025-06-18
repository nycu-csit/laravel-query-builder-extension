<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as Orchestra;
use NycuCsit\SpatieLaravelQueryBuilderExtension\Tests\TestClasses\Models\TestModel;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class TestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function setUpDatabase(Application $app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('category');
        });
    }

    protected function createQueryFromFilterRequest(array $filters, string $model = null): QueryBuilder
    {
        $model ??= TestModel::class;

        $request = new Request([
            'filter' => $filters,
        ]);

        return QueryBuilder::for($model, $request);
    }
}
