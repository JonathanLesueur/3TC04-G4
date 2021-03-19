<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }


    public function searchWithName($name)
    {
        return $this->createQueryBuilder('b')
        ->andWhere('lower(b.title) LIKE :name')
        ->setParameter('name', '%'.$name.'%')
        ->getQuery()
        ->getResult();
    }

    public function findWithAssociation($limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('b')
        ->where('b.association IS NOT NULL')
        ->addOrderBy('b.id', 'DESC');
        if($offset != null) {
            $qb->setFirstResult($offset);
        }
        if($limit != null) {
            $qb->setMaxResults($limit);
        }
        $qb->getQuery()
        ->getResult();

        return $qb;
    }
    public function findWithoutAssociation($limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('b')
        ->where('b.association IS NULL')
        ->addOrderBy('b.id', 'DESC');
            if($offset != null) {
                $qb->setFirstResult($offset);
            }
            if($limit != null) {
                $qb->setMaxResults($limit);
            }
        $qb->getQuery()
        ->getResult();
        
        return $qb;
    }
}
