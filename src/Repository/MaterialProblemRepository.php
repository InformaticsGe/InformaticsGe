<?php

namespace App\Repository;

use App\Entity\MaterialProblem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaterialProblem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialProblem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialProblem[]    findAll()
 * @method MaterialProblem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialProblemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaterialProblem::class);
    }

    // /**
    //  * @return MaterialProblem[] Returns an array of MaterialProblem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialProblem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
