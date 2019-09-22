<?php

namespace App\Repository;

use App\Entity\ProblemSubmissionResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProblemSubmissionResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProblemSubmissionResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProblemSubmissionResult[]    findAll()
 * @method ProblemSubmissionResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProblemSubmissionResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProblemSubmissionResult::class);
    }

    // /**
    //  * @return ProblemSubmissionResult[] Returns an array of ProblemSubmissionResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProblemSubmissionResult
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
