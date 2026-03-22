<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $lastName;

    #[Assert\NotNull]
    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Type('string'),
        new Assert\Regex('/^\+\d{10,15}$/')
    ])]
    public array $phoneNumbers = [];
}
