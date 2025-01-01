<?php

namespace App\Entity;

use App\Repository\OrderOrdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderOrdRepository::class)]
class OrderOrd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(nullable: true,type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfPurchase = null;

    #[ORM\Column(nullable: true,type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true,type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, Claim>
     */
    #[ORM\OneToMany(targetEntity: Claim::class, mappedBy: 'orderOrd')]
    private Collection $claim;

    #[ORM\ManyToOne(inversedBy: 'orderOrd')]
    private ?User $user = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'orderOrd')]
    private Collection $transaction;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'orderOrd')]
    private Collection $product;

    public function __construct()
    {
        $this->claim = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateOfPurchase(): ?\DateTimeInterface
    {
        return $this->dateOfPurchase;
    }

    public function setDateOfPurchase(\DateTimeInterface $dateOfPurchase): static
    {
        $this->dateOfPurchase = $dateOfPurchase;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Claim>
     */
    public function getClaim(): Collection
    {
        return $this->claim;
    }

    public function addClaim(Claim $claim): static
    {
        if (!$this->claim->contains($claim)) {
            $this->claim->add($claim);
            $claim->setOrderOrd($this);
        }

        return $this;
    }

    public function removeClaim(Claim $claim): static
    {
        if ($this->claim->removeElement($claim)) {
            // set the owning side to null (unless already changed)
            if ($claim->getOrderOrd() === $this) {
                $claim->setOrderOrd(null);
            }
        }

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

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction->add($transaction);
            $transaction->setOrderOrd($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getOrderOrd() === $this) {
                $transaction->setOrderOrd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $product->setOrderOrd($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrderOrd() === $this) {
                $product->setOrderOrd(null);
            }
        }

        return $this;
    }
}
