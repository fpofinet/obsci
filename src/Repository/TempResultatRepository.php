<?php

namespace App\Repository;

use App\Entity\TempResultat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TempResultat>
 *
 * @method TempResultat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempResultat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempResultat[]    findAll()
 * @method TempResultat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempResultatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempResultat::class);
    }

//    /**
//     * @return TempResultat[] Returns an array of TempResultat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TempResultat
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
