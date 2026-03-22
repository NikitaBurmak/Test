<?php

namespace App\Infrastructure\IpLocate;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Client
{
    public function __construct(
        private HttpClientInterface $client
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
    public function getCountryByIp(string $ip): ?string
    {
        $response = $this->client->request(
            'GET',
            "https://www.iplocate.io/api/lookup/$ip"
        );

        $data = $response->toArray();

        return $data['country'] ?? null;
    }
}
