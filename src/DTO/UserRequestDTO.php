<?php

namespace App\DTO;

use AllowDynamicProperties;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDTO
{
    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\NotBlank]
    public string $lastName;

    public array $phoneNumbers = [];
}
