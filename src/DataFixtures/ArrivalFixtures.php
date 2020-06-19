<?php

namespace App\DataFixtures;

use App\Entity\Arrival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArrivalFixtures extends Fixture
{
    const LOCATIONS = [
        'CADA',
        'Préfecture',
        'Hôpital',
        'CAF',
        'Médecin',
        'Mairie'
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;

        foreach (self::LOCATIONS as $location) {
            $arrival = new Arrival();
            $arrival->setName($location);
            $manager->persist($arrival);
            $this->addReference('arrival_' . $i, $arrival);
            $i++;
        }

        $manager->flush();
    }
}
