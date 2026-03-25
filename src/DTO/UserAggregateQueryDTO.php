<?php

namespace App\DTO;

use OpenApi\Attributes as OA;

class UserAggregateQueryDTO
{
    public function __construct(
        public string $sort = 'asc',
        public int $limit = 10,
        public int $cursor = 0
    ) {
    }
}
