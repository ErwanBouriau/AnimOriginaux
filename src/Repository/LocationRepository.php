<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param user l'utilisateur dont on veut les locations 
     * @return Location[] Returns an array of Location objects
    */
    public function getLocationsByUser($user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :val')
            ->setParameter('val', $user)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function getLocationByAnimal($animal): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.animal = :val')
            ->setParameter('val', $animal)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
