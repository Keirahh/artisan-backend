<?php

namespace App\Repository;

use App\Entity\LocationRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocationRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocationRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocationRegion[]    findAll()
 * @method LocationRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocationRegion::class);
    }

    // /**
    //  * @return LocationRegion[] Returns an array of LocationRegion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LocationRegion
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
