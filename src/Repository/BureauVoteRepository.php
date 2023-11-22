<?php

namespace App\Repository;

use App\Entity\BureauVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BureauVote>
 *
 * @method BureauVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method BureauVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method BureauVote[]    findAll()
 * @method BureauVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BureauVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BureauVote::class);
    }

//    /**
//     * @return BureauVote[] Returns an array of BureauVote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BureauVote
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
