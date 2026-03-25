<?php

namespace App\tests\Message;

use App\DTO\CreateUserInputDTO;
use App\Message\UserMessage;
use PHPUnit\Framework\TestCase;

class UserMessageTest extends TestCase
{
    public function testCreateUserMessage(): void
    {
        $input = new CreateUserInputDTO('John', 'Doe', ['+1234567890'], '192.168.1.1');

        $message = new UserMessage($input, '192.168.1.1');

        $this->assertSame($input, $message->input);
        $this->assertEquals('192.168.1.1', $message->ip);
    }

    public function testGetInput(): void
    {
        $input = new CreateUserInputDTO('Jane', 'Smith', [], '10.0.0.1');

        $message = new UserMessage($input, '10.0.0.1');

        $this->assertEquals('Jane', $message->input->firstName);
        $this->assertEquals('Smith', $message->input->lastName);
    }

    public function testGetIp(): void
    {
        $input = new CreateUserInputDTO('Test', 'User', [], '127.0.0.1');
        $message = new UserMessage($input, '127.0.0.1');

        $this->assertEquals('127.0.0.1', $message->ip);
    }
}
