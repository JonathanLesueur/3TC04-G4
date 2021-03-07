<?php

namespace App\Repository;

use App\Entity\OfferComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfferComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferComment[]    findAll()
 * @method OfferComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferComment::class);
    }

    // /**
    //  * @return OfferComment[] Returns an array of OfferComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfferComment
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
