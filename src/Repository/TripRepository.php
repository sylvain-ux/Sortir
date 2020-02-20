<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Form\SearchType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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

    //ENORME REQUETE AVEC QUERY BUILDER
    public function findFilters(
        $schoolId,
        $dateStartId,
        $dateEndId,
        $organizerId,
        $RadioOrNot,
        $pastTrip,

        Request $request,
        UserInterface $user
    ) {

        $qb = $this->createQueryBuilder('t');
        $subQb = $this->createQueryBuilder('sq');
        $now = new \DateTime();


        //Recherche en fonction des ville organisatrice :
        if ($schoolId != null) {
            $qb
                ->where('t.school = :schoolId')
                ->setParameter(':schoolId', $schoolId->getId());
        }

        // Recherche en fonction des dates :
        //Date de debut :
        if ($dateStartId != null) {
            $qb
                ->andWhere('t.dateTimeStart >= :dateStartId')
                ->setParameter(':dateStartId', $dateStartId);
        }

        //Date de fin :
        if ($dateEndId != null) {
            $qb
                ->andWhere('t.dateTimeStart <= :dateEndId')
                ->setParameter(':dateEndId', $dateEndId);
        }

        //Recherche pour afficher les sorties dont je suis l'organisatreur/trice :
        if ($organizerId != null) {
            echo("coucou");
            $qb
                ->andWhere('t.user = :organizerId')
                ->setParameter(':organizerId', $user->getId());
        }


        if ($RadioOrNot != null) {
            //Recherche pour afficher les sorties auxquelles je suis inscrit/e :
            if ($RadioOrNot == 1) {
                $qb
                    ->innerJoin('t.users', 'sqb', 'with', "sqb.id in (:user)")
                    ->setParameter(':user', $user->getId());
            }

            //Recherche pour afficher les sorties auxquelles je ne suis pas inscrit/e :
            if ($RadioOrNot == 0) {
                $subQb
                    ->innerJoin('sq.users', 'sqb2', 'with', "sqb2.id in (:user)")
                    ->setParameter(':user', $user->getId());

                $qb
                    ->addselect('i')
                    ->leftJoin('t.users', 'i')
                    ->andWhere('t NOT IN ('.$subQb->getDQL().')')
                    ->setParameter(':user', $user);
            }
        }

        //Recherche pour afficher les sorties passées :
        if ($pastTrip != null) {
            $qb
                ->andWhere('t.dateTimeStart <= :now')
                ->setParameter(':now', $now);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @Return Trip[] Returns an array of Trip objects ordered by date ASC
     */
    public function findAllByDate()
    {
        return $this->createQueryBuilder('t')
                    ->orderBy('t.registDeadline', 'ASC')
                    ->getQuery()
                    ->getResult()
            ;
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
