<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Users::class);
        $this->em = $em;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Users $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Users $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Validation adress email s'il existe
     *
     * @param [type] $email
     * @return Users|null
     */
    public function check_email($email): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Validation adress email s'il existe
     * @param [type] $email
     * @return Users|null
     */
    public function check_username($username): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val1')
            ->setParameter('val1', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * get all users id and image from the database
     */
    public function get_allImg()
    {
        $sql="SELECT id, email,image FROM users";
        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAll();
       return $result;
    }
}
