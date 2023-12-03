<?php

namespace App\Repository;

use App\Entity\ResultatSuperviseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultatSuperviseur>
 *
 * @method ResultatSuperviseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultatSuperviseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultatSuperviseur[]    findAll()
 * @method ResultatSuperviseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultatSuperviseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultatSuperviseur::class);
    }

//    /**
//     * @return ResultatSuperviseur[] Returns an array of ResultatSuperviseur objects
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

//    public function findOneBySomeField($value): ?ResultatSuperviseur
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
