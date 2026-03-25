<?php

namespace App\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserInputDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\NotBlank]
        public string $lastName,

        public array $phoneNumbers = [],

        public string $ip = ''
    ) {}
}
