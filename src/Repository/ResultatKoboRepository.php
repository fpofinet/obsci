<?php

namespace App\Repository;

use App\Entity\ResultatKobo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultatKobo>
 *
 * @method ResultatKobo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultatKobo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultatKobo[]    findAll()
 * @method ResultatKobo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultatKoboRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultatKobo::class);
    }

//    /**
//     * @return ResultatKobo[] Returns an array of ResultatKobo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResultatKobo
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
