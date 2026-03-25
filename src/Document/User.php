<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Repository\UserRepository;
use OpenApi\Attributes as OA;

#[ODM\Document(collection: "users")]
#[ODM\Index(keys: ["id" => "asc"])]
#[OA\Schema(description: 'User entity')]
class User
{
    #[ODM\Id(strategy: "INCREMENT")]
    private ?int $id = null;

    #[ODM\Field(type: "string")]
    private string $firstName;

    #[ODM\Field(type: "string")]
    private string $lastName;

    /** @var string[] */
    #[ODM\Field(type: "collection")]
    private array $phoneNumbers = [];

    #[ODM\Field(type: "string")]
    private string $ip;

    #[ODM\Field(type: "string")]
    private string $country;

    #[ODM\Field(type: "date_immutable")]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /** @return string[] */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /** @param string[] $phoneNumbers */
    public function setPhoneNumbers(array $phoneNumbers): self
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
