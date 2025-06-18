<?php

namespace NycuCsit\SpatieLaravelQueryBuilderExtension\Tests\TestClasses\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $table = 'test_models';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'category',
    ];
}
