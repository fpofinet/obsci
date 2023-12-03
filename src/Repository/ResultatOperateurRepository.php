<?php

namespace App\Repository;

use App\Entity\ResultatOperateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultatOperateur>
 *
 * @method ResultatOperateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultatOperateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultatOperateur[]    findAll()
 * @method ResultatOperateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultatOperateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultatOperateur::class);
    }

//    /**
//     * @return ResultatOperateur[] Returns an array of ResultatOperateur objects
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

//    public function findOneBySomeField($value): ?ResultatOperateur
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
