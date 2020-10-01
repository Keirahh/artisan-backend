<?php

namespace App\Repository;

use App\Controller\LocationController;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Controller\RoleController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;
    private $roleController;
    private $locationController;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, ManagerRegistry $registry, EntityManagerInterface $manager, RoleController $roleController, LocationController $locationController)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->roleController = $roleController;
        $this->locationController = $locationController;
        $this->encoder = $encoder;
    }

    public function saveUser($firstName, $lastName, $email, $password, $birthdate, $role, $location)
    {
        $newUser = new User();
        // $encoded = $this->encoder->encodePassword($newUser,$password);
        $role = $this->roleController->getEntity($role);
        $location = $this->locationController->getEntity($location);
        $newUser
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPassword($password)
            ->setBirthdate($birthdate)
            ->setRole($role)
            ->setLocation($location);

        $this->manager->persist($newUser);
        $this->manager->flush();

        return true;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
