<?php

namespace App\Resolver;

use App\DTO\CreateUserInputDTO;
use App\DTO\UserRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRequestDTOArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $content = $request->getContent();

        if (empty($content)) {
            return;
        }

        if ($argument->getType() === CreateUserInputDTO::class) {
            $ip = $request->getClientIp() ?? '';

            $userData = $this->deserializeToUserRequestDTO($content);

            $errors = $this->validator->validate($userData);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                throw new \InvalidArgumentException(implode(', ', $errorMessages));
            }

            yield new CreateUserInputDTO(
                $userData->firstName,
                $userData->lastName,
                $userData->phoneNumbers,
                $ip
            );

            return;
        }

        if ($argument->getType() === UserRequestDTO::class) {
            $dto = $this->deserializeToUserRequestDTO($content);

            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                throw new \InvalidArgumentException(implode(', ', $errorMessages));
            }

            yield $dto;
        }
    }

    private function deserializeToUserRequestDTO(string $content): UserRequestDTO
    {
        $result = $this->serializer->deserialize($content, UserRequestDTO::class, 'json');

        if (!$result instanceof UserRequestDTO) {
            throw new \RuntimeException('Failed to deserialize UserRequestDTO');
        }

        return $result;
    }
}
