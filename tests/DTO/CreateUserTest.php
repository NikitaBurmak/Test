<?php

namespace App\tests\DTO;

use App\DTO\CreateUserInputDTO;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    public function testCreateUserInputDTO(): void
    {
        $dto = new CreateUserInputDTO('John', 'Doe', ['+1234567890'], '192.168.1.1');

        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
        $this->assertEquals(['+1234567890'], $dto->phoneNumbers);
        $this->assertEquals('192.168.1.1', $dto->ip);
    }

    public function testCreateUserInputDTODefaultValues(): void
    {
        $dto = new CreateUserInputDTO('John', 'Doe');

        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
        $this->assertEquals([], $dto->phoneNumbers);
        $this->assertEquals('', $dto->ip);
    }

    public function testPhoneNumbersMultiple(): void
    {
        $dto = new CreateUserInputDTO('John', 'Doe', ['+1234567890', '+0987654321']);

        $this->assertCount(2, $dto->phoneNumbers);
    }
}
