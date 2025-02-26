<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findBySearchAndSort(?string $search, string $sort, string $direction): array
    {
        $query = $this->createQueryBuilder('u');
    
        // Filtre de recherche
        if ($search) {
            $query->andWhere('u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search OR u.ville LIKE :search')
                  ->setParameter('search', '%' . $search . '%');
        }
    
        // Tri dynamique
        if (in_array($sort, ['id', 'nom', 'prenom', 'role', 'specialite', 'email', 'ville', 'adresse', 'dateNaissance']) 
            && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy('u.' . $sort, $direction);
        }
    
        return $query->getQuery()->getResult();
    }
}
