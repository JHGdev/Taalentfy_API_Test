<?php

namespace App\Repository;

use App\Entity\KnowledgeAssignments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KnowledgeAssignments|null find($id, $lockMode = null, $lockVersion = null)
 * @method KnowledgeAssignments|null findOneBy(array $criteria, array $orderBy = null)
 * @method KnowledgeAssignments[]    findAll()
 * @method KnowledgeAssignments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnowledgeAssignmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KnowledgeAssignments::class);
    }

    // /**
    //  * @return KnowledgeAssignments[] Returns an array of KnowledgeAssignments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KnowledgeAssignments
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
