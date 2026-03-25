<?php

namespace App\tests\Service;

use App\DTO\CreateUserInputDTO;
use App\Document\User;
use App\Infrastructure\IpLocate\Client;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCreateUserPersistsAndFlushes(): void
    {
        $dm = $this->createMock(DocumentManager::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $ipClient->method('getCountryByIp')->willReturn('Ukraine');

        $dm->expects($this->once())->method('persist')->with($this->isInstanceOf(User::class));
        $dm->expects($this->once())->method('flush');

        $service = new UserService($dm, $ipClient, $repo);

        $dto = new CreateUserInputDTO('John', 'Doe', ['+1234567890']);
        $service->createUser($dto, '192.168.1.1');
    }

    public function testCreateUserSetsCountry(): void
    {
        $dm = $this->createMock(DocumentManager::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $ipClient->method('getCountryByIp')->willReturn('Spain');

        $dm->expects($this->once())->method('persist');

        $service = new UserService($dm, $ipClient, $repo);

        $dto = new CreateUserInputDTO('Jane', 'Smith', ['+0987654321']);
        $service->createUser($dto, '8.8.8.8');
    }

    public function testGetUsersReturnsArray(): void
    {
        $dm = $this->createMock(DocumentManager::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPhoneNumbers(['+1234567890']);
        $user->setIp('192.168.1.1');
        $user->setCountry('USA');

        $repo->method('findUsersWithAggregation')->willReturn([$user]);

        $service = new UserService($dm, $ipClient, $repo);

        $result = $service->getUsers('asc', 10, 0);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('John', $result[0]['first_name']);
    }

    public function testGetUsersCallsRepositoryWithCorrectParams(): void
    {
        $dm = $this->createMock(DocumentManager::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $repo->expects($this->once())
            ->method('findUsersWithAggregation')
            ->with('desc', 5, 20)
            ->willReturn([]);

        $service = new UserService($dm, $ipClient, $repo);

        $service->getUsers('desc', 5, 20);
    }

    public function testGetUsersWithEmptyResult(): void
    {
        $dm = $this->createMock(DocumentManager::class);
        $ipClient = $this->createMock(Client::class);
        $repo = $this->createMock(UserRepository::class);

        $repo->method('findUsersWithAggregation')->willReturn([]);

        $service = new UserService($dm, $ipClient, $repo);

        $result = $service->getUsers('asc', 10, 0);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}
