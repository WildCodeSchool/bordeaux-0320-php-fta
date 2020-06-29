<?php

namespace App\DataFixtures;

use App\Entity\Arrival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArrivalFixtures extends Fixture
{
    const LOCATIONS = [
        0 => 'CADA',
        1 => 'Préfecture',
        2 => 'Hôpital',
        3 => 'CAF',
        4 => 'Médecin',
        5 => 'Mairie'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::LOCATIONS as $key => $location) {
            $arrival = new Arrival();
            $arrival->setName($location);
            $manager->persist($arrival);
            $this->addReference('arrival_' . $key, $arrival);
        }

        $manager->flush();
    }
}
