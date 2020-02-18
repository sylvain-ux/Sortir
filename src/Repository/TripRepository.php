<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }


    public function findBySchoolID($id)
    {
        $entityManager = $this->getEntityManager();

        $dql = <<<DQL
SELECT t FROM App\Entity\Trip t WHERE t.school = :id
DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }

    // Recherche en fonction des dates :

    //Date de debut :
    public function findByDateStart($id)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
SELECT t FROM App\Entity\Trip t WHERE t.dateTimeStart >= :id
DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }

    //Date de fin :
    public function findByDateEnd($id)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
SELECT t FROM App\Entity\Trip t WHERE :id <= t.dateTimeStart
DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }

    //Recherche pour afficher les sorties dont je suis l'organisatreur/trice :
    public function findByMyTrip($id)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
 SELECT t FROM App\Entity\Trip t WHERE  t.user = :id
 DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }

    //Recherche pour afficher les sorties auxquelles je suis inscrit/e :
    public function findByMyRegistration($id)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
  SELECT t FROM App\Entity\Trip t JOIN t.users usersTrip WHERE usersTrip.id = :id
 DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }

    //Recherche pour afficher les sorties auxquelles je ne suis pas inscrit/e :
    public function findByMyNotRegistration($id)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
  SELECT t FROM App\Entity\Trip t JOIN t.users usersTrip WHERE usersTrip.id = :id
 DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(':id', $id);
        $result = $query->getResult();

        return $result;
    }


    //Recherche pour afficher les sorties passées :
    public function findByPastTrip()
    {
        $entityManager = $this->getEntityManager();
        $now = new \DateTime();
        $dql = <<<DQL
SELECT t FROM App\Entity\Trip t WHERE t.dateTimeStart < :now
DQL;
        $query = $entityManager->createQuery($dql);
        $query->setParameter(":now",$now);
        $result = $query->getResult();

        return $result;
    }


    // /**
    //  * @return Trip[] Returns an array of Trip objects
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
    public function findOneBySomeField($value): ?Trip
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
