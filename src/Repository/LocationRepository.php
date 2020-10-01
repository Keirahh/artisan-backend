<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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


    public function findAllLocations($page, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return new Paginator($query);
    }

    public function searchLocation($value)
    {
        $qb = $this->createQueryBuilder('l');
        $qb
            ->leftjoin('l.city', 'c')
            ->leftjoin('l.zip', 'z')
            ->leftjoin('l.departement', 'd')
            ->leftjoin('l.region', 'r')
            ->orwhere('c.name LIKE :val')
            ->orWhere('z.zip LIKE :val')
            ->orWhere('d.name LIKE :val')
            ->orWhere('r.name LIKE :val')
            ->orderBy('c.name', 'ASC')
            ->setParameter('val', $value . "%")
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Location[] Returns an array of Location objects
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
    public function findOneBySomeField($value): ?Location
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
