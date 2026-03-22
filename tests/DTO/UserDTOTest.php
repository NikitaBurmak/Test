<?php

namespace App\tests\DTO;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO;
use App\DTO\UserRequestDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserDTOTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->validator = static::getContainer()
            ->get(ValidatorInterface::class);
    }

    public function testValidDTO(): void
    {
        $dto = new UserRequestDTO();

        $dto->firstName = "Nikita";
        $dto->lastName = "Burmak";
        $dto->phoneNumbers = ["+380123456789"];

        $errors = $this->validator->validate($dto);

        $this->assertCount(0, $errors);
    }

    public function testInvalidFirstName(): void
    {
        $dto = new UserRequestDTO();

        $dto->firstName = "";
        $dto->lastName = "Burmak";
        $dto->phoneNumbers = ["+380123456789"];

        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
    }

    public function testInvalidPhone(): void
    {
        $dto = new UserRequestDTO();

        $dto->firstName = "Nikita";
        $dto->lastName = "Burmak";
        $dto->phoneNumbers = ["123"];

        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
    }
}
