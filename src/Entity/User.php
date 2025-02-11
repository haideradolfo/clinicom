<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/",
        message: "Le nom doit commencer par une majuscule et ne contenir que des lettres"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/",
        message: "Le prénom doit commencer par une majuscule"
    )]
    private ?string $prenom = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de naissance est obligatoire")]
    #[Assert\LessThanOrEqual(
        "-18 years",
        message: "Vous devez avoir au moins 18 ans"
    )]
    private ?\DateTimeInterface $date_naissance = null;

    #[ORM\Column(enumType: Role::class)]
    #[Assert\NotNull(message: "Le rôle doit être spécifié")]
    private ?Role $role = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La spécialité est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/",
        message: "La spécialité doit commencer par une majuscule"
    )]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(mode: 'strict', message: "Email invalide")]
    #[Assert\Regex(
        pattern: "/^[a-z0-9]+\.[a-z0-9]+@[a-z]+\.[a-z]{2,}$/i",
        message: "Format email invalide (ex: jean.dupont@domaine.com)"
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Length(
        min: 8,
        max: 64,
        minMessage: "Minimum {{ limit }} caractères",
        maxMessage: "Maximum {{ limit }} caractères"
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
        message: "Le mot de passe doit contenir au moins 1 majuscule, 1 chiffre et 1 caractère spécial"
    )]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/",
        message: "La ville doit commencer par une majuscule"
    )]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ0-9][a-zà-éèêëîïôùûüçœ0-9\-, ']*$/",
        message: "L'adresse doit commencer par une majuscule ou un chiffre"
    )]
    private ?string $adresse = null;

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

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
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
}
