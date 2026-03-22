<?php

namespace App\tests\Handler;

use App\Controller\UserController;
use App\DTO\UserRequestDTO;
use App\Message\UserMessage;
use App\MessageHandler\UserMessageHandler;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\MessageHandler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserHandlerTest extends WebTestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testHandlerCallsService(): void
    {
        $service = $this->createMock(UserService::class);

        $service
            ->expects($this->once())
            ->method('createUser');

        $handler = new UserMessageHandler($service);

        $dto = new UserRequestDTO();
        $dto->firstName = "Nikita";
        $dto->lastName = "Burmak";
        $dto->phoneNumbers = ["+380123456789"];

        $message = new UserMessage($dto, "127.0.0.1");

        $handler($message);
    }
}
