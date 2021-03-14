<?php

namespace App\Repository;

use App\Entity\RapidPostChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RapidPostChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapidPostChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapidPostChannel[]    findAll()
 * @method RapidPostChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapidPostChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapidPostChannel::class);
    }

    // /**
    //  * @return RapidPostChannel[] Returns an array of RapidPostChannel objects
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
    public function findOneBySomeField($value): ?RapidPostChannel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function searchWithName($name)
    {
        return $this->createQueryBuilder('r')
        ->andWhere('r.name LIKE :name')
        ->setParameter('name', '%'.$name.'%')
        ->getQuery()
        ->getOneOrNullResult();
    }
}
