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
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
        message: "Le nom doit commencer par une lettre majuscule et ne contenir que des lettres et des espaces."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
        message: "Le prénom doit commencer par une lettre majuscule et ne contenir que des lettres et des espaces."
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)] 
    private ?string $role = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: "L'adresse email n'est pas valide."
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
#[Assert\Length(
    min: 8,
    minMessage: "Le mot de passe doit contenir au moins 8 caractères.",
    groups: ['registration'] // <-- Groupe ajouté ici
)]
#[Assert\Regex(
    pattern: "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
    message: "Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.",
    groups: ['registration'] // <-- Groupe ajouté ici
)]
private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    // Getters/Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->mdp; // Symfony attend cette méthode pour l'authentification
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    #[Assert\Callback]
    public function validateAge(ExecutionContextInterface $context): void
    {
        $today = new \DateTime();
        $age = $today->diff($this->dateNaissance)->y;

        if ($age < 18) {
            $context->buildViolation('Vous devez avoir 18 ans ou plus.')
                ->atPath('dateNaissance')
                ->addViolation();
        }
    }

    // Méthodes UserInterface
    public function getRoles(): array
    {
        // Si le rôle est null ou vide, le définir comme ROLE_PATIENT
        if (!$this->role) {
            return ['ROLE_PATIENT'];  // Le rôle par défaut
        }
    
        // Ajouter "ROLE_" si nécessaire
        $roles = [$this->role];
    
        foreach ($roles as &$role) {
            if (!str_starts_with($role, 'ROLE_')) {
                $role = 'ROLE_' . strtoupper($role);
            }
        }
    
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // Effacer les données sensibles temporaires
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
