<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TripFixtures extends Fixture implements DependentFixtureInterface
{
    const VOLUNTEERS = [
        0 => 'volunteer_0',
        1 => 'volunteer_1',
        2 => 'volunteer_2',
        3 => 'volunteer_3',
        4 => 'volunteer_4',
        5 => 'volunteer_5'
    ];

    const BENEFICIARIES = [
        0 => 'beneficiary_0',
        1 => 'beneficiary_1',
        2 => 'beneficiary_2',
        3 => 'beneficiary_3',
        4 => 'beneficiary_4',
        5 => 'beneficiary_5'
    ];

    const DEPARTURES = [
        'departure_0',
        'departure_1',
        'departure_2',
        'departure_3',
        'departure_4',
        'departure_5'
    ];

    const ARRIVALS = [
        'arrival_0',
        'arrival_1',
        'arrival_2',
        'arrival_3',
        'arrival_4',
        'arrival_5'
    ];

    const DATES_EMPTY_TRIP = [
        '2020-08-13 13:30',
        '2020-08-18 16:00',
        '2020-08-19 17:00',
        '2020-08-20 18:00',
        '2020-08-21 16:30',
        '2020-08-23 14:00'
    ];

    const DATES_FULL_TRIP = [
        '2020-08-16 11:00',
        '2020-08-24 09:30',
        '2020-08-25 10:30',
        '2020-08-26 08:00',
        '2020-08-27 11:15',
        '2020-08-30 08:30'
    ];


    public function load(ObjectManager $manager)
    {
        foreach (self::BENEFICIARIES as $key => $beneficiary) {
            $tripEmpty = new Trip();
            $tripEmpty->setDate(new DateTime(self::DATES_EMPTY_TRIP[$key]));
            $tripEmpty->setDeparture($this->getReference(array_rand(array_flip(self::DEPARTURES))));
            $tripEmpty->setArrival($this->getReference(array_rand(array_flip(self::ARRIVALS))));
            $tripEmpty->setVolunteer(null);
            $tripEmpty->setBeneficiary($this->getReference(self::BENEFICIARIES[$key]));
            $tripEmpty->setIsMorning(0);
            $tripEmpty->setIsAfternoon(1);
            $tripEmpty->setBeneficiary($this->getReference($beneficiary));
            $manager->persist($tripEmpty);
        }

        foreach (self::BENEFICIARIES as $key => $beneficiary) {
            $tripFull = new Trip();
            $tripFull->setDate(new DateTime(self::DATES_FULL_TRIP[$key]));
            $tripFull->setDeparture($this->getReference(array_rand(array_flip(self::DEPARTURES))));
            $tripFull->setArrival($this->getReference(array_rand(array_flip(self::ARRIVALS))));
            $tripFull->setVolunteer($this->getReference(self::VOLUNTEERS[$key]));
            $tripFull->setBeneficiary($this->getReference(self::BENEFICIARIES[$key]));
            $tripFull->setIsMorning(1);
            $tripFull->setIsAfternoon(0);
            $tripFull->setBeneficiary($this->getReference($beneficiary));
            $manager->persist($tripFull);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
