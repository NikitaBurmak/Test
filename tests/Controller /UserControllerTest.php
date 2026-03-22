<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Message\UserMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class UserControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testCreateUser(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(function (UserMessage $message, array $stamps = []) {
                return new Envelope($message);
            });

        self::getContainer()->set('messenger.default_bus', $messageBus);

        $this->client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'firstName' => 'Nikita',
                'lastName' => 'Burmak',
                'phoneNumbers' => ['+380123456789']
            ])
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('queued', $data['status']);
    }

    public function testGetUsers(): void
    {
        $this->client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetUsersWithParams(): void
    {
        $this->client->request(
            'GET',
            '/users?limit=5&cursor=10&sort=desc'
        );

        $this->assertResponseStatusCodeSame(200);
    }
}
