<?php

namespace App\Repository;

use App\Controller\LocationController;
use App\Controller\RoleController;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var LocationController
     */
    private $locationController;
    /**
     * @var RoleController
     */
    private $roleController;

    /**
     * UserRepository constructor.
     * @param RoleController $roleController
     * @param LocationController $locationController
     * @param ManagerRegistry $registry
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(RoleController $roleController, LocationController $locationController, ManagerRegistry $registry, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $passwordEncoder;
        $this->locationController = $locationController;
        $this->roleController = $roleController;
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $birthdate
     * @param $location
     * @param $email
     * @param $password
     * @param $role
     * @return User
     * @throws \Exception
     */
    public function saveUser($firstName, $lastName, $birthdate, $location, $email, $password, $role)
    {
        $errors = [];
        $roleEntity = $this->roleController->getEntity($role);
        $locationEntity = $this->locationController->getEntity($location);

        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setBirthdate($birthdate);
        $user->setLocation($locationEntity);
        $user->setEmail($email);
        $user->setPassword($encodedPassword);
        $user->setRole($roleEntity);

        try {
            $this->_em->persist($user);
            $this->_em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception("Wrong email");
        } catch (\Exception $e) {
            throw new \Exception("Unable to save new user at this time.");
        }

        return $user;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
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
