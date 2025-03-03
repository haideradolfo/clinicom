<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
        message: "Le nom doit commencer par une majuscule"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
        message: "Le prénom doit commencer par une majuscule"
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $role = 'ROLE_PATIENT';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "Email invalide")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 8,
        minMessage: "8 caractères minimum",
        groups: ['registration']
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/",
        message: "Majuscule, chiffre et caractère spécial requis",
        groups: ['registration']
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 20)]
    #[Assert\Regex(
        pattern: "/^\+[0-9]{9,15}$/",
        message: "Format international requis (+21612345678)"
    )]
    private ?string $phone = null;

    // Getters/Setters
    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }

    public function getSpecialite(): ?string { return $this->specialite; }
    public function setSpecialite(?string $specialite): static { $this->specialite = $specialite; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(string $ville): static { $this->ville = $ville; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getDateNaissance(): ?\DateTimeInterface { return $this->dateNaissance; }
    public function setDateNaissance(\DateTimeInterface $dateNaissance): static { $this->dateNaissance = $dateNaissance; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(string $phone): static { $this->phone = $phone; return $this; }

    // UserInterface
    public function getRoles(): array {
        $roles = [$this->role];
        foreach ($roles as &$role) {
            if (!str_starts_with($role, 'ROLE_')) {
                $role = 'ROLE_' . strtoupper($role);
            }
        }
        return array_unique($roles);
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string {
        return $this->email;
    }

    #[Assert\Callback]
    public function validateAge(ExecutionContextInterface $context): void {
        $today = new \DateTime();
        $age = $today->diff($this->dateNaissance)->y;
        if ($age < 18) {
            $context->buildViolation('18 ans minimum')
                ->atPath('dateNaissance')
                ->addViolation();
        }
    }
}