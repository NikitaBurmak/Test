<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "firstName", type: "string", length: 255)]
    private string $firstName;

    #[ORM\Column(name: "lastName", type: "string", length: 255)]
    private string $lastName;

    #[ORM\Column(name: "phoneNumbers", type: "json")]
    private array $phoneNumbers = [];

    #[ORM\Column(name: "ip", type: "string", length: 45)]
    private string $ip;

    #[ORM\Column(name: "country", type: "string", length: 255)]
    private string $country;

    #[ORM\Column(name: "createdAt", type: "datetime_immutable")]
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

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

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
