<?php

namespace App\DataFixtures;

use App\Entity\Departure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartureFixtures extends Fixture
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
            $departure = new Departure();
            $departure->setName($location);
            $manager->persist($departure);
            $this->addReference('departure_' . $i, $departure);
            $i++;
        }

        $manager->flush();
    }
}
