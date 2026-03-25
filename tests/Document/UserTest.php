<?php

namespace App\tests\Document;

use App\Document\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testSettersAndGetters(): void
    {
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPhoneNumbers(['+1234567890']);
        $user->setIp('192.168.1.1');
        $user->setCountry('USA');

        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals(['+1234567890'], $user->getPhoneNumbers());
        $this->assertEquals('192.168.1.1', $user->getIp());
        $this->assertEquals('USA', $user->getCountry());
    }

    public function testSetPhoneNumbersEmpty(): void
    {
        $user = new User();
        $user->setPhoneNumbers([]);

        $this->assertEquals([], $user->getPhoneNumbers());
    }

    public function testSetPhoneNumbersMultiple(): void
    {
        $user = new User();
        $user->setPhoneNumbers(['+1234567890', '+0987654321']);

        $this->assertCount(2, $user->getPhoneNumbers());
    }

    public function testGetCreatedAt(): void
    {
        $user = new User();

        $this->assertInstanceOf(\DateTimeInterface::class, $user->getCreatedAt());
    }

    public function testSetCreatedAt(): void
    {
        $user = new User();
        $date = new \DateTimeImmutable('2024-01-01');
        $user->setCreatedAt($date);

        $this->assertEquals($date, $user->getCreatedAt());
    }
}
