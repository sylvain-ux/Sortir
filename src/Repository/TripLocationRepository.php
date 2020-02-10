<?php

namespace App\Repository;

use App\Entity\TripLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TripLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripLocation[]    findAll()
 * @method TripLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TripLocation::class);
    }

    // /**
    //  * @return TripLocation[] Returns an array of TripLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TripLocation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
