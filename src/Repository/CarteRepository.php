<?php

namespace App\Repository;

use App\Entity\Carte;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Carte>
 *
 * @method Carte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carte[]    findAll()
 * @method Carte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarteRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        parent::__construct($registry, Carte::class);
        $this->em = $em;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Carte $entity, bool $flush = true): void
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
    public function remove(Carte $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getCarteList()
    {
        $sql = "SELECT
        tableau.id 'idTab',
        tableau.nom 'nomTab',
        carte.`id`,
        carte.`title`,
        carte.`date`,
        carte.`description`,
        carte.`labels_id`,
        carte.`user_id`,
        carte.`tableau_id`
    FROM
        tableau
    LEFT JOIN `carte` ON carte.tableau_id = tableau.id";

        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();
       return $result;
    }




    public function carteList($id)
    {
        $sql="SELECT * FROM `carte` WHERE id=$id;";

        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();
       return $result;
    }

    // /**
    //  * @return Carte[] Returns an array of Carte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Carte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
