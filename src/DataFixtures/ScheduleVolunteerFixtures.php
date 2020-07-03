<?php

namespace App\DataFixtures;

use App\Entity\ScheduleVolunteer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class ScheduleVolunteerFixtures extends Fixture implements DependentFixtureInterface
{
    const VOLUNTEERS = [
        0 => 'volunteer_0',
        1 => 'volunteer_1',
    ];

    const DATES_EMPTY_TRIP = [
        '2020-08-13',
        '2020-08-18'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::VOLUNTEERS as $key => $volunteer) {
            $scheduleVolunteer = new ScheduleVolunteer();
            $scheduleVolunteer->setUser($this->getReference($volunteer));
            $scheduleVolunteer->setDate(new DateTime(self::DATES_EMPTY_TRIP[$key]));
            $scheduleVolunteer->setIsAfternoon(true);
            $scheduleVolunteer->setIsMorning(false);
            $manager->persist($scheduleVolunteer);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
