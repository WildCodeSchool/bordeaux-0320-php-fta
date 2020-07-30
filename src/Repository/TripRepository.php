<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function matchingAvailability($isMorning, $isAfternoon, $date)
    {
        return $this->createQueryBuilder('t')
            ->orWhere('t.isMorning = :isMorning')
            ->orWhere('t.isAfternoon = :isAfternoon')
            ->andWhere('t.date LIKE :date')
            ->andWhere('t.volunteer is NULL')
            ->setParameter('isMorning', $isMorning)
            ->setParameter('isAfternoon', $isAfternoon)
            ->setParameter('date', '%' . $date . '%')
            ->getQuery()
            ->getResult();
    }
}
