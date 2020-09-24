<?php

namespace App\Repository;

use App\Entity\LocationDepartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocationDepartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocationDepartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocationDepartement[]    findAll()
 * @method LocationDepartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationDepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocationDepartement::class);
    }

    // /**
    //  * @return LocationDepartement[] Returns an array of LocationDepartement objects
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
    public function findOneBySomeField($value): ?LocationDepartement
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
