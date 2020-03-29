<?php

namespace App\Repository;

use App\Entity\ApproFuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ApproFuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApproFuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApproFuel[]    findAll()
 * @method ApproFuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApproFuelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApproFuel::class);
    }

    // /**
    //  * @return ApproFuel[] Returns an array of ApproFuel objects
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
    public function findOneBySomeField($value): ?ApproFuel
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
