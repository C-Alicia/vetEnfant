<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true,length: 255)]
    private ?string $street = null;

    #[ORM\Column(nullable: true,length: 255)]
    private ?string $streetDelivery = null;

    #[ORM\Column(nullable: true,length: 10)]
    private ?string $postalCode = null;

    #[ORM\Column(nullable: true,length: 150)]
    private ?string $city = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'address')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetDelivery(): ?string
    {
        return $this->streetDelivery;
    }

    public function setStreetDelivery(string $streetDelivery): static
    {
        $this->streetDelivery = $streetDelivery;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
