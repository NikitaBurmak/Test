<?php

namespace App\DTO;

use AllowDynamicProperties;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[AllowDynamicProperties]
#[OA\Schema(description: 'User data for request/response')]
class UserRequestDTO
{
    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\NotBlank]
    public string $lastName;

    public array $phoneNumbers = [];
}
