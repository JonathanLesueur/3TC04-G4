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


}
