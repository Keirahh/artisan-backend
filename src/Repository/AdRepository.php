<?php

namespace App\Repository;

use App\Controller\UserController;
use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * AdRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, UserController $userController)
    {
        parent::__construct($registry, Ad::class);
        $this->userController = $userController;
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
    public function saveAd($title, $description, $user)
    {
        $userEntity = $this->userController->getEntity($user);
        $ad = new Ad();

        $ad->setTitle($title);
        $ad->setDescription($description);
        $ad->setUser($userEntity);


        $this->_em->persist($ad);
        $this->_em->flush();

        return $ad;
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
