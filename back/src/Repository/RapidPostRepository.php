<?php

namespace App\Repository;

use App\Entity\RapidPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RapidPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapidPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapidPost[]    findAll()
 * @method RapidPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapidPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapidPost::class);
    }

    // /**
    //  * @return RapidPost[] Returns an array of RapidPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RapidPost
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
