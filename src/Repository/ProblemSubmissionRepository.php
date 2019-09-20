<?php

namespace App\Repository;

use App\Entity\ProblemSubmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProblemSubmission|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProblemSubmission|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProblemSubmission[]    findAll()
 * @method ProblemSubmission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProblemSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProblemSubmission::class);
    }

    /**
     * Get last submissions.
     * @param int $count
     *
     * @return ProblemSubmission[] Returns an array of ProblemSubmission objects
     */
    public function findLast($count = 1)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return ProblemSubmission[] Returns an array of ProblemSubmission objects
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
    public function findOneBySomeField($value): ?ProblemSubmission
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
