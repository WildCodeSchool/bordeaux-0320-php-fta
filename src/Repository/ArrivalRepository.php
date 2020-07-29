<?php

namespace App\Repository;

use App\Entity\Arrival;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Arrival|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrival|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrival[]    findAll()
 * @method Arrival[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrivalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrival::class);
    }
}
