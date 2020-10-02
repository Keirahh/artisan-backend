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
use Doctrine\ORM\EntityManagerInterface;
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
    private $manager;
    private $passwordEncoder;
    private $locationController;
    private $roleController;

    public function __construct(RoleController $roleController, LocationController $locationController, ManagerRegistry $registry, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->locationController = $locationController;
        $this->roleController = $roleController;
    }

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
            $this->manager->persist($user);
            $this->manager->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse([
                "The email provided already has an account!" => $errors
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                "Unable to save new user at this time." => $errors
            ], 400);
        }

        if ($errors) {
            return new JsonResponse([
                'errors' => $errors
            ], 400);
        }

        return new JsonResponse([
            'user created' => $user
        ], Response::HTTP_CREATED);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->manager->persist($user);
        $this->manager->flush();
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
