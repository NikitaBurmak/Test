<?php

namespace App\Service;

use App\DTO\CreateUserInputDTO;
use App\DTO\UserRequestDTO;
use App\Document\User;
use App\Infrastructure\IpLocate\Client;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserService
{
    public function __construct(
        private DocumentManager $dm,
        private Client                 $ipLocateClient,
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function createUser(CreateUserInputDTO $dto, string $ip): void
    {
        try {
            $country = 'Unknown';
            try {
                $country = $this->ipLocateClient->getCountryByIp($ip) ?? 'Unknown';
            } catch (\Throwable $e) {
            }

            $user = new User()
                ->setFirstName($dto->firstName)
                ->setLastName($dto->lastName)
                ->setPhoneNumbers($dto->phoneNumbers)
                ->setIp($ip)
                ->setCountry($country);

            $this->dm->persist($user);
            $this->dm->flush();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Cannot save user: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array<string, mixed>[]
     */
    public function getUsers(string $sort = 'asc', int $limit = 10, int $cursor = 0): array
    {
        try {
            $users = $this->userRepository->findUsersWithAggregation($sort, $limit, $cursor);
        } catch (MongoDBException $e) {
            throw new \RuntimeException('Cannot fetch users', 0, $e);
        }

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
