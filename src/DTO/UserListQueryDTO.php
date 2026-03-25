<?php

namespace App\DTO;

use AllowDynamicProperties;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class UserListQueryDTO
{
    public function __construct(
        #[Assert\Choice(['asc', 'desc'])]
        public string $sort = 'asc',

        #[Assert\Range(min: 1, max: 100)]
        public int $limit = 10,

        #[Assert\Range(min: 0)]
        public int $cursor = 0
    ) {}
}
