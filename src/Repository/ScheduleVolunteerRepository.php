<?php

namespace App\Repository;

use App\Entity\ScheduleVolunteer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScheduleVolunteer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleVolunteer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleVolunteer[]    findAll()
 * @method ScheduleVolunteer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleVolunteerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleVolunteer::class);
    }
}
