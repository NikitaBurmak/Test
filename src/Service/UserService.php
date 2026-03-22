<?php

namespace App\Service;

use App\DTO\UserRequestDTO;
use App\Entity\User;
use App\Infrastructure\IpLocate\Client;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private Client                 $ipLocateClient,
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function createUser(UserRequestDTO $dto, string $ip): void
    {
        $user = new User()
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setPhoneNumbers($dto->phoneNumbers)
            ->setIp($ip)
            ->setCountry(
                $this->ipLocateClient->getCountryByIp($ip) ?? 'Unknown'
            );

        $this->em->persist($user);
        $this->em->flush();
    }

    public function getUsers(string $sort = 'asc', int $limit = 10, int $cursor = 0): array
    {
        $users = $this->userRepository->findUsersWithCursor($sort, $limit, $cursor);

        $result = [];
        foreach ($users as $user) {
            $result[] = [
                "id" => $user->getId(),
                "first_name" => $user->getFirstName(),
                "last_name" => $user->getLastName(),
                "phone_numbers" => $user->getPhoneNumbers(),
                "ip" => $user->getIp(),
                "country" => $user->getCountry(),
            ];
        }

        return $result;
    }
}
