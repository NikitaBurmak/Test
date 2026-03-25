<?php

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(description: 'Aggregate query parameters for user listing')]
class UserAggregateQueryDTO
{
    public function __construct(
        #[OA\Property(description: 'Sort order', enum: ['asc', 'desc'], default: 'asc')]
        public string $sort = 'asc',

        #[OA\Property(description: 'Limit number of results', default: 10, minimum: 1, maximum: 100)]
        public int $limit = 10,

        #[OA\Property(description: 'Cursor for pagination', default: 0, minimum: 0)]
        public int $cursor = 0
    ) {
    }
}
