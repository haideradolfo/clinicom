<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
#[ORM\HasLifecycleCallbacks] // 👈 Ajout pour activer les événements PrePersist et PreUpdate
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionDemande = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantDemande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeSongDemader = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoDemande = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); // 👈 Assure une valeur par défaut à l'instanciation
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionDemande(): ?string
    {
        return $this->descriptionDemande;
    }

    public function setDescriptionDemande(string $descriptionDemande): static
    {
        $this->descriptionDemande = $descriptionDemande;
        return $this;
    }

    public function getMontantDemande(): ?float
    {
        return $this->montantDemande;
    }

    public function setMontantDemande(?float $montantDemande): static
    {
        $this->montantDemande = $montantDemande;
        return $this;
    }

    public function getTypeSongDemader(): ?string
    {
        return $this->typeSongDemader;
    }

    public function setTypeSongDemader(?string $typeSongDemader): static
    {
        $this->typeSongDemader = $typeSongDemader;
        return $this;
    }

    public function getPhotoDemande(): ?string
    {
        return $this->photoDemande;
    }

    public function setPhotoDemande(?string $photoDemande): static
    {
        $this->photoDemande = $photoDemande;
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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
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
}
