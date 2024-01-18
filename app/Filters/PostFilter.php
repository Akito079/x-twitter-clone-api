<?php
namespace App\Filters;

use App\Filters\ApiFilter;

class PostFilter extends ApiFilter{
    protected $safeParms = [
        "content" =>["like"],
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'like' => 'LIKE',
    ];
}
