<?php

namespace App\Repository;

use App\Entity\Artisan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method Artisan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artisan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artisan[]    findAll()
 * @method Artisan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtisanRepository extends ServiceEntityRepository
{
    /**
     * ArtisanRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artisan::class);
    }

    // /**
    //  * @return Artisan[] Returns an array of Artisan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artisan
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $user
     * @param $siret
     * @param $company
     * @param $activity
     * @return Artisan
     * @throws \Exception
     */
    public function saveArtisan($user, $siret, $company, $activity)
    {
        $errors = [];

        $artisan = new Artisan();

        $artisan->setUser($user);
        $artisan->setSiret($siret);
        $artisan->setCompany($company);
        $artisan->setActivity($activity);

        try {
            $this->_em->persist($artisan);
            $this->_em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception("The SIRET is already linked to an account");
        } catch (\Exception $e) {
            throw new \Exception("Unable to save new artisan at this time.");
        }

        return $artisan;
    }
}
