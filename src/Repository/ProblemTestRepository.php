<?php

namespace App\Repository;

use App\Entity\ProblemTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProblemTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProblemTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProblemTest[]    findAll()
 * @method ProblemTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProblemTestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProblemTest::class);
    }

    // /**
    //  * @return ProblemTest[] Returns an array of ProblemTest objects
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
    public function findOneBySomeField($value): ?ProblemTest
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
