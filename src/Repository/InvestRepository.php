<?php

namespace App\Repository;

use App\Entity\Invest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invest>
 *
 * @method Invest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invest[]    findAll()
 * @method Invest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invest::class);
    }

//    /**
//     * @return Invest[] Returns an array of Invest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Invest
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
