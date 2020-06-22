<?php

namespace App\DataFixtures;

use App\Entity\Departure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartureFixtures extends Fixture
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
            $departure = new Departure();
            $departure->setName($location);
            $manager->persist($departure);
            $this->addReference('departure_' . $key, $departure);
        }

        $manager->flush();
    }
}
