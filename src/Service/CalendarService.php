<?php

namespace App\Service;

use App\Entity\User;

/**
 * Class CalendarService
 * @package App\Service
 */
class CalendarService
{
    /**
     * @param array $availabilityUsers
     * @return array
     */
    public static function transformToJson(Array $availabilityUsers): array
    {
        $table = [];
        $increment = 0;
        foreach ($availabilityUsers as $user) {
            $table[$increment]['date'] = $user->getDate()->format('d/m/Y');
            $table[$increment]['isMorning'] = $user->getIsMorning();
            $table[$increment]['isAfternoon'] = $user->getIsAfternoon();
            $increment++;
        }
        return $table;
    }
}
