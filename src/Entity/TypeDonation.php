<?php

namespace App\Entity;

use App\Repository\TypeDonationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDonationRepository::class)]
class TypeDonation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeDeDonation = null;

    /**
     * @var Collection<int, Donation>
     */
    #[ORM\OneToMany(targetEntity: Donation::class, mappedBy: 'TypeDonation')]
    private Collection $donations;

    public function __construct()
    {
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDeDonation(): ?string
    {
        return $this->typeDeDonation;
    }

    public function setTypeDeDonation(string $typeDeDonation): static
    {
        $this->typeDeDonation = $typeDeDonation;

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): static
    {
        if (!$this->donations->contains($donation)) {
            $this->donations->add($donation);
            $donation->setTypeDonation($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): static
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getTypeDonation() === $this) {
                $donation->setTypeDonation(null);
            }
        }

        return $this;
    }
}
