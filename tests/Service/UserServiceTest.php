<?php

namespace App\tests\Service;

use App\DTO\UserRequestDTO;
use App\Entity\User;
use App\Infrastructure\IpLocate\Client;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserServiceTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateUserPersistsUser(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $ipClient
            ->method('getCountryByIp')
            ->willReturn('Ukraine');

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $service = new UserService($em, $ipClient, $repo);

        $dto = new UserRequestDTO();
        $dto->firstName = "Nikita";
        $dto->lastName = "Burmak";
        $dto->phoneNumbers = ["+380123456789"];

        $service->createUser($dto, "127.0.0.1");
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateUserSetsCountry(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $ipClient
            ->method('getCountryByIp')
            ->willReturn('Spain');

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $service = new UserService($em, $ipClient, $repo);

        $dto = new UserRequestDTO();
        $dto->firstName = "John";
        $dto->lastName = "Doe";
        $dto->phoneNumbers = ["+111111111"];

        $service->createUser($dto, "8.8.8.8");
    }


    public function testGetUsersReturnsArray(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $user = new User();
        $user->setFirstName("John");
        $user->setLastName("Doe");
        $user->setPhoneNumbers(["+123"]);
        $user->setIp("127.0.0.1");
        $user->setCountry("USA");

        $repo
            ->method('findUsersWithCursor')
            ->willReturn([$user]);

        $service = new UserService($em, $ipClient, $repo);

        $result = $service->getUsers('asc', 10, 0);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testGetUsersCallsRepository(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $ipClient = $this->createMock(Client::class);

        $repo = $this->createMock(UserRepository::class);

        $repo
            ->expects($this->once())
            ->method('findUsersWithCursor')
            ->with('desc', 5, 20)
            ->willReturn([]);

        $service = new UserService($em, $ipClient, $repo);

        $service->getUsers('desc', 5, 20);

    }
}
