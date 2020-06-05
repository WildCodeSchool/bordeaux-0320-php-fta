<?php

namespace App\Service;

use App\Entity\ScheduleVolunteer;
use App\Entity\User;

/**
 * Class CalendarService
 * @package App\Service
 */
class CalendarService
{
    public static function addAvailability($request, $user, $entityManager): void
    {
        $schedule = new ScheduleVolunteer();
        $date = new \DateTime($request->request->get('datePicker'));
        $schedule->setDate($date);
        $schedule->setUserId($user);
        $schedule->setIsAfternoon($request->request->has('afternoon') ? true : false);
        $schedule->setIsMorning($request->request->has('morning') ? true : false);
        $entityManager->persist($schedule);
        $entityManager->flush();
    }

    /**
     * @param User $userAvailability
     * @return array
     */
    public static function transformToJson($userAvailability): array
    {
        $table = [];
        $increment = 0;
        foreach ($userAvailability as $user) {
            $table[$increment]['date'] = $user->getDate()->format('d/m/Y');
            $table[$increment]['isMorning'] = $user->getIsMorning();
            $table[$increment]['isAfternoon'] = $user->getIsAfternoon();
            $increment++;
        }
        return $table;
    }
}
