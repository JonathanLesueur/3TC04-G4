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

    public function searchWithName($name)
    {
        return $this->createQueryBuilder('r')
        ->andWhere('lower(r.title) LIKE :name')
        ->orWhere('lower(r.content) LIKE :name')
        ->setParameter('name', '%'.$name.'%')
        ->getQuery()
        ->getResult();
    }
}
