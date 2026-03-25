<?php

namespace App\Message;

use App\DTO\CreateUserInputDTO;

readonly class UserMessage
{
    public function __construct(
        public CreateUserInputDTO $input,
        public string $ip
    ) {}
}
