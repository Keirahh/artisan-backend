<?php

namespace App\Repository;

use App\Controller\UserController;
use App\Controller\LocationController;
use App\Entity\Ad;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    /**
     * @var UserController
     */
    private $userController;
    /**
     * @var LocationController
     */
    private $locationController;

    /**
     * AdRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, UserController $userController, LocationController $locationController)
    {
        parent::__construct($registry, Ad::class);
        $this->userController = $userController;
        $this->locationController = $locationController;
    }

    /**
     * @param $title
     **/

    /**
     * @param $description
     **/

    /**
     * @param $user
     **/
    public function saveAd($title, $description, $user, $location)
    {
        $userEntity = $this->userController->getEntity($user);
        $locationEntity = $this->locationController->getEntity($location);
        $ad = new Ad();

        $ad->setTitle($title);
        $ad->setDescription($description);
        $ad->setUser($userEntity);
        $ad->setLocation($locationEntity);
        $date = new DateTime(date('Y-m-d H:i'));
        $ad->setCreatedAt($date);
        $this->_em->persist($ad);
        $this->_em->flush();

        return $ad;
    }


    /**
     * @return int|mixed|string
     */
    public function findByTitle($page, $limit, $title)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->andWhere('p.title LIKE :val')
            ->setParameter('val', "%" . $title . "%")
            ->orderBy('p.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }

    /**
     * @return int|mixed|string
     */
    public function findRecent()
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findByUserId($id)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->andWhere('a.user = :val')
            ->setParameter('val', $id)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Ad[] Returns an array of Ad objects
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
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
