<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TripFixtures extends Fixture implements DependentFixtureInterface
{
    const VOLUNTEERS = [
        'volunteer_0',
        'volunteer_1',
    ];

    const BENEFICIARIES = [
        'beneficiary_0',
        'beneficiary_1'
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::BENEFICIARIES as $beneficiaryID) {
            $tripEmpty = new Trip();
            $tripEmpty->setDate(new \DateTime('2020-07-12 10:00'));
            $tripEmpty->setDeparture($this->getReference('departure_' . ($i + 1)));
            $tripEmpty->setArrival($this->getReference('arrival_' . ($i + 1)));
            $tripEmpty->setVolunteer(null);
            $tripEmpty->addUser($this->getReference($beneficiaryID));
            $manager->persist($tripEmpty);
            $this->addReference('tripEmpty_' . $i, $tripEmpty);
            $i++;
        }

        foreach (self::BENEFICIARIES as $beneficiaryID) {
            foreach (self::VOLUNTEERS as $volunteerID) {
                $tripFull = new Trip();
                $tripFull->setDate(new \DateTime('2020-06-15 15:00'));
                $tripFull->setDeparture($this->getReference('departure_' . $i));
                $tripFull->setArrival($this->getReference('arrival_' . $i));
                $tripFull->setVolunteer($this->getReference($volunteerID));
                $tripFull->addUser($this->getReference($beneficiaryID));
                $manager->persist($tripFull);
                $this->addReference('tripFull_' . $i, $tripFull);
                $i++;
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
