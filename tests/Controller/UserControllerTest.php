<?php

namespace App\Tests\Controller;

use App\DTO\CreateUserInputDTO;
use App\DTO\UserAggregateQueryDTO;
use App\Message\UserMessage;
use App\Repository\UserRepository;
use App\Document\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class UserControllerTest extends TestCase
{
    private MessageBusInterface $bus;
    private UserRepository $repository;
    private \App\Controller\UserController $controller;

    protected function setUp(): void
    {
        $this->bus = $this->createMock(MessageBusInterface::class);
        $this->repository = $this->createMock(UserRepository::class);
        $this->controller = new \App\Controller\UserController();
    }

    public function testCreateDispatchesMessageAndReturns202(): void
    {
        $input = new CreateUserInputDTO('John', 'Doe', ['+1234567890'], '192.168.1.1');

        $this->bus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (UserMessage $message) use ($input) {
                return $message->input->firstName === $input->firstName
                    && $message->ip === $input->ip;
            }))
            ->willReturnCallback(fn($message) => new \Symfony\Component\Messenger\Envelope($message));

        $response = $this->controller->create($input, $this->bus);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(202, $response->getStatusCode());
        $this->assertEquals(['status' => 'queued'], json_decode($response->getContent(), true));
    }

    public function testListReturnsUsersFromRepository(): void
    {
        $query = new UserAggregateQueryDTO('asc', 10, 0);

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPhoneNumbers(['+1234567890']);
        $user->setIp('192.168.1.1');
        $user->setCountry('US');

        $this->repository->expects($this->once())
            ->method('findUsersWithAggregation')
            ->with('asc', 10, 0)
            ->willReturn([$user]);

        $response = $this->controller->list($query, $this->repository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data);
        $this->assertEquals('John', $data[0]['first_name']);
        $this->assertEquals('Doe', $data[0]['last_name']);
    }

    public function testListWithEmptyResult(): void
    {
        $query = new UserAggregateQueryDTO('asc', 10, 0);

        $this->repository->expects($this->once())
            ->method('findUsersWithAggregation')
            ->willReturn([]);

        $response = $this->controller->list($query, $this->repository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(0, $data);
    }

    public function testListWithRawArrayFromAggregation(): void
    {
        $query = new UserAggregateQueryDTO('desc', 5, 100);

        $rawUser = [
            '_id' => '456',
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'phoneNumbers' => ['+9876543210'],
            'ip' => '10.0.0.1',
            'country' => 'GB'
        ];

        $this->repository->expects($this->once())
            ->method('findUsersWithAggregation')
            ->with('desc', 5, 100)
            ->willReturn([$rawUser]);

        $response = $this->controller->list($query, $this->repository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data);
        $this->assertEquals('456', $data[0]['id']);
        $this->assertEquals('Jane', $data[0]['first_name']);
        $this->assertEquals('GB', $data[0]['country']);
    }

    public function testCountReturnsTotalUserCount(): void
    {
        $this->repository->expects($this->once())
            ->method('countUsers')
            ->willReturn(42);

        $response = $this->controller->count($this->repository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['count' => 42], $data);
    }

    public function testCountWithZeroUsers(): void
    {
        $this->repository->expects($this->once())
            ->method('countUsers')
            ->willReturn(0);

        $response = $this->controller->count($this->repository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['count' => 0], $data);
    }
}
