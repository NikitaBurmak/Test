<?php

namespace App\Tests\Resolver;

use App\DTO\CreateUserInputDTO;
use App\DTO\UserRequestDTO;
use App\Resolver\UserRequestDTOArgumentResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserRequestDTOArgumentResolverTest extends TestCase
{
    private UserRequestDTOArgumentResolver $resolver;
    private $serializer;
    private $validator;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->resolver = new UserRequestDTOArgumentResolver(
            $this->serializer,
            $this->validator
        );
    }

    public function testResolveWithEmptyContent(): void
    {
        $request = Request::create('/users', 'POST', [], [], [], [], '');
        $argument = new ArgumentMetadata('request', UserRequestDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(0, $result);
    }

    public function testResolveCreateUserInputDTOSuccess(): void
    {
        $userDTO = new UserRequestDTO();
        $userDTO->firstName = 'John';
        $userDTO->lastName = 'Doe';
        $userDTO->phoneNumbers = ['+1234567890'];

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userDTO);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John', 'lastName' => 'Doe', 'phoneNumbers' => ['+1234567890']])
        );
        $argument = new ArgumentMetadata('request', CreateUserInputDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(CreateUserInputDTO::class, $result[0]);
    }

    public function testResolveCreateUserInputDTOValidationError(): void
    {
        $userDTO = new UserRequestDTO();
        $userDTO->firstName = 'John';
        $userDTO->lastName = 'Doe';
        $userDTO->phoneNumbers = ['+1234567890'];

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userDTO);

        $violation = new ConstraintViolation('Error message', null, [], null, 'path', 'invalid');
        $violations = new ConstraintViolationList([$violation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John', 'lastName' => 'Doe', 'phoneNumbers' => ['+1234567890']])
        );
        $argument = new ArgumentMetadata('request', CreateUserInputDTO::class, false, false, null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Error message');

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveUserRequestDTOSuccess(): void
    {
        $userDTO = new UserRequestDTO();
        $userDTO->firstName = 'John';
        $userDTO->lastName = 'Doe';
        $userDTO->phoneNumbers = ['+1234567890'];

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userDTO);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John', 'lastName' => 'Doe', 'phoneNumbers' => ['+1234567890']])
        );
        $argument = new ArgumentMetadata('request', UserRequestDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(UserRequestDTO::class, $result[0]);
        $this->assertEquals('John', $result[0]->firstName);
    }

    public function testResolveUserRequestDTOValidationError(): void
    {
        $userDTO = new UserRequestDTO();
        $userDTO->firstName = 'John';
        $userDTO->lastName = 'Doe';
        $userDTO->phoneNumbers = ['+1234567890'];

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userDTO);

        $violation = new ConstraintViolation('Validation failed', null, [], null, 'path', 'invalid');
        $violations = new ConstraintViolationList([$violation]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John', 'lastName' => 'Doe', 'phoneNumbers' => ['+1234567890']])
        );
        $argument = new ArgumentMetadata('request', UserRequestDTO::class, false, false, null);

        $this->expectException(\InvalidArgumentException::class);

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveReturnsEmptyForUnsupportedType(): void
    {
        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John'])
        );
        $argument = new ArgumentMetadata('request', 'UnsupportedClass', false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(0, $result);
    }
}
