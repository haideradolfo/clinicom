<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionDonation = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantDonation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeSang = null; // ✅ Correction ici

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoDonation = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: TypeDonation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeDonation $TypeDonation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionDonation(): ?string
    {
        return $this->descriptionDonation;
    }

    public function setDescriptionDonation(string $descriptionDonation): static
    {
        $this->descriptionDonation = $descriptionDonation;
        return $this;
    }

    public function getMontantDonation(): ?float
    {
        return $this->montantDonation;
    }

    public function setMontantDonation(?float $montantDonation): static
    {
        $this->montantDonation = $montantDonation;
        return $this;
    }

    public function getTypeSang(): ?string
    {
        return $this->typeSang;
    }

    public function setTypeSang(?string $typeSang): static
    {
        $this->typeSang = $typeSang;
        return $this;
    }

    public function getPhotoDonation(): ?string
    {
        return $this->photoDonation;
    }

    public function setPhotoDonation(?string $photoDonation): static
    {
        $this->photoDonation = $photoDonation;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getTypeDonation(): ?TypeDonation
    {
        return $this->TypeDonation;
    }

    public function setTypeDonation(?TypeDonation $TypeDonation): static
    {
        $this->TypeDonation = $TypeDonation;
        return $this;
    }
}
