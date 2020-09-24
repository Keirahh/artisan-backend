<?php

namespace App\Repository;

use App\Entity\LocationCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocationCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocationCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocationCity[]    findAll()
 * @method LocationCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationCityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocationCity::class);
    }

    // /**
    //  * @return LocationCity[] Returns an array of LocationCity objects
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
    public function findOneBySomeField($value): ?LocationCity
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
