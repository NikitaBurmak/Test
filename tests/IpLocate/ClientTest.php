<?php

namespace App\tests\IpLocate;

use App\Infrastructure\IpLocate\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClientTest extends TestCase
{
    public function testClientCanBeInstantiated(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $client = new Client($httpClient);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testGetCountryByIpWithValidResponse(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->method('toArray')->willReturn(['country' => 'US']);
        $httpClient->method('request')->willReturn($response);

        $client = new Client($httpClient);
        $result = $client->getCountryByIp('8.8.8.8');

        $this->assertEquals('US', $result);
    }

    public function testGetCountryByIpReturnsNullOnEmptyResponse(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->method('toArray')->willReturn([]);
        $httpClient->method('request')->willReturn($response);

        $client = new Client($httpClient);
        $result = $client->getCountryByIp('127.0.0.1');

        $this->assertNull($result);
    }
}
