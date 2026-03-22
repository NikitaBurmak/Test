<?php

namespace App\Controller;

use App\DTO\UserRequestDTO;
use App\Message\UserMessage;
use App\Service\UserService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


class UserController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[OA\Post(
        path: "/users",
        summary: "Create user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "firstName", type: "string"),
                    new OA\Property(property: "lastName", type: "string"),
                    new OA\Property(
                        property: "phoneNumbers",
                        type: "array",
                        items: new OA\Items(type: "string")
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User queued"
            )
        ]
    )]
    #[Route('/users', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserRequestDTO $dto, MessageBusInterface $bus, Request $request, UserService $userService): JsonResponse
    {
        $ip = $request->getClientIp();

        $bus->dispatch(new UserMessage($dto, $ip));

        return new JsonResponse([
            'status' => 'queued'
        ]);
    }

    #[OA\Get(
        path: "/users",
        summary: "Get users",
        parameters: [
            new OA\Parameter(
                name: "sort",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User list"
            )
        ]
    )]
    #[Route('/users', methods: ['GET'])]
    public function list(Request $request, UserService $userService): JsonResponse
    {
        $limit = (int) $request->query->get('limit', 10);
        $cursor = (int) $request->query->get('cursor', 0);
        $sort = $request->query->get('sort', 'asc');

        $users = $userService->getUsers($sort, $limit, $cursor);

        return new JsonResponse($users);
    }
}
