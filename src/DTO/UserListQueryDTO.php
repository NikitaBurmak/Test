<?php

namespace App\DTO;

use AllowDynamicProperties;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[AllowDynamicProperties]
class UserListQueryDTO
{
    public function __construct(
        #[Assert\Choice(['asc', 'desc'])]
        #[OA\Property(description: 'Sort order', example: 'asc')]
        public string $sort = 'asc',

        #[Assert\Range(min: 1, max: 100)]
        #[OA\Property(description: 'Limit number of results', example: 10)]
        public int $limit = 10,

        #[Assert\Range(min: 0)]
        #[OA\Property(description: 'Cursor for pagination', example: 0)]
        public int $cursor = 0
    ) {}
}
