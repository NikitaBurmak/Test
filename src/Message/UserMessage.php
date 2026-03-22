<?php

namespace App\Message;

use App\DTO\UserRequestDTO;

readonly class UserMessage
{
    public function __construct(
        public UserRequestDTO $dto,
        public string         $ip
    )
    {
    }
}
