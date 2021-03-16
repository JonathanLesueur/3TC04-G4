<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function searchWithName($name)
    {
        return $this->createQueryBuilder('o')
        ->andWhere('lower(o.title) LIKE :name')
        ->setParameter('name', '%'.$name.'%')
        ->getQuery()
        ->getResult();
    }

    public function searchCustom($name, $type, $price)
    {
        $qb = $this->createQueryBuilder('o');
        if($name != '') {
            $qb->andWhere('lower(o.title) LIKE :name')->setParameter('name', '%'.$name.'%');
        }
        if($type != '') {
            $qb->andWhere('o.type = :type')->setParameter('type', $type);
        }
        if($price != '') {
            $qb->andWhere('o.price <= :price')->setParameter('price', $price);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
