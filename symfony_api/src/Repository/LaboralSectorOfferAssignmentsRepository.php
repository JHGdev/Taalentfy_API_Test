<?php

namespace App\Repository;

use App\Entity\LaboralSectorOfferAssignments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LaboralSectorOfferAssignments|null find($id, $lockMode = null, $lockVersion = null)
 * @method LaboralSectorOfferAssignments|null findOneBy(array $criteria, array $orderBy = null)
 * @method LaboralSectorOfferAssignments[]    findAll()
 * @method LaboralSectorOfferAssignments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaboralSectorOfferAssignmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LaboralSectorOfferAssignments::class);
    }

    // /**
    //  * @return LaboralSectorOfferAssignments[] Returns an array of LaboralSectorOfferAssignments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LaboralSectorOfferAssignments
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
