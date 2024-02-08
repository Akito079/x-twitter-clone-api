<?php
namespace App\Filters;

use App\Filters\ApiFilter;

class PostFilter extends ApiFilter{
    protected $safeParms = [
        "content" =>["like"],
        "userId" => ["eq"],
    ];

    protected $columnMap = [
        'userId' => 'user_id'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'like' => 'LIKE',
    ];
}
