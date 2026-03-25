<?php

namespace App\tests\Handler;

use App\DTO\CreateUserInputDTO;
use App\Message\UserMessage;
use App\MessageHandler\UserMessageHandler;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserHandlerTest extends TestCase
{
    public function testInvokeCallsService(): void
    {
        $service = $this->createMock(UserService::class);

        $service->expects($this->once())
            ->method('createUser')
            ->with($this->isInstanceOf(CreateUserInputDTO::class), '192.168.1.1');

        $handler = new UserMessageHandler($service);

        $dto = new CreateUserInputDTO('John', 'Doe', ['+1234567890']);
        $message = new UserMessage($dto, '192.168.1.1');

        $handler($message);
    }

    public function testInvokeWithDifferentData(): void
    {
        $service = $this->createMock(UserService::class);

        $service->expects($this->once())
            ->method('createUser')
            ->with(
                $this->callback(fn($dto) => $dto->firstName === 'Jane' && $dto->lastName === 'Smith'),
                '10.0.0.1'
            );

        $handler = new UserMessageHandler($service);

        $dto = new CreateUserInputDTO('Jane', 'Smith', ['+0987654321']);
        $message = new UserMessage($dto, '10.0.0.1');

        $handler($message);
    }
}
