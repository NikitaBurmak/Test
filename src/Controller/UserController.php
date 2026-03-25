<?php

namespace App\Controller;

use App\DTO\CreateUserInputDTO;
use App\DTO\UserAggregateQueryDTO;
use App\Message\UserMessage;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/users', name: 'app_user_create', methods: ['POST'])]
    public function create(CreateUserInputDTO $input, MessageBusInterface $bus): JsonResponse
    {
        $bus->dispatch(new UserMessage($input, $input->ip));
        return new JsonResponse(['status' => 'queued'], 202);
    }

    #[Route('/users', name: 'app_user_list', methods: ['GET'])]
    public function list(UserAggregateQueryDTO $query, UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findUsersWithAggregation(
            $query->sort,
            $query->limit,
            $query->cursor
        );

        $result = [];
        foreach ($users as $user) {
            if (is_array($user)) {
                $result[] = [
                    "id" => $user['_id'] ?? null,
                    "first_name" => $user['firstName'] ?? null,
                    "last_name" => $user['lastName'] ?? null,
                    "phone_numbers" => $user['phoneNumbers'] ?? [],
                    "ip" => $user['ip'] ?? null,
                    "country" => $user['country'] ?? null,
                ];
            } else {
                $result[] = [
                    "id" => $user->getId(),
                    "first_name" => $user->getFirstName(),
                    "last_name" => $user->getLastName(),
                    "phone_numbers" => $user->getPhoneNumbers(),
                    "ip" => $user->getIp(),
                    "country" => $user->getCountry(),
                ];
            }
        }

        return new JsonResponse($result);
    }

    #[Route('/users/count', name: 'app_user_count', methods: ['GET'])]
    public function count(UserRepository $userRepository): JsonResponse
    {
        $count = $userRepository->countUsers();
        return new JsonResponse(['count' => $count]);
    }
}
