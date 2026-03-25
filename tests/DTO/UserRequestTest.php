<?php

namespace App\tests\DTO;

use App\DTO\UserRequestDTO;
use PHPUnit\Framework\TestCase;

class UserRequestTest extends TestCase
{
    public function testCreateUserRequestDTO(): void
    {
        $dto = new UserRequestDTO();
        $dto->firstName = 'John';
        $dto->lastName = 'Doe';

        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
    }

    public function testDefaultPhoneNumbers(): void
    {
        $dto = new UserRequestDTO();

        $this->assertIsArray($dto->phoneNumbers);
        $this->assertEmpty($dto->phoneNumbers);
    }

    public function testSetMultiplePhoneNumbers(): void
    {
        $dto = new UserRequestDTO();
        $dto->phoneNumbers = ['+1234567890', '+0987654321'];

        $this->assertCount(2, $dto->phoneNumbers);
        $this->assertEquals('+1234567890', $dto->phoneNumbers[0]);
        $this->assertEquals('+0987654321', $dto->phoneNumbers[1]);
    }

    public function testIpField(): void
    {
        $dto = new UserRequestDTO();
        $dto->ip = '192.168.1.1';

        $this->assertEquals('192.168.1.1', $dto->ip);
    }

    public function testFullUserRequest(): void
    {
        $dto = new UserRequestDTO();
        $dto->firstName = 'Jane';
        $dto->lastName = 'Smith';
        $dto->phoneNumbers = ['+1111111111'];
        $dto->ip = '10.0.0.1';

        $this->assertEquals('Jane', $dto->firstName);
        $this->assertEquals('Smith', $dto->lastName);
        $this->assertEquals('+1111111111', $dto->phoneNumbers[0]);
        $this->assertEquals('10.0.0.1', $dto->ip);
    }
}
