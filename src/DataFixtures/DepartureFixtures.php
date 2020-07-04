<?php

namespace App\DataFixtures;

use App\Entity\Departure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartureFixtures extends Fixture
{
    const LOCATIONS = [
        'CADA' => '<i class="material-icons">location_city</i>',
        'Préfecture' => '<i class="material-icons">location_city</i>',
        'CAF' => '<i class="material-icons">location_city</i>',
        'Mairie' => '<i class="material-icons">location_city</i>',
        'Hôpital' => '<i class="material-icons">local_hospital</i>',
        'Médecin' => '<i class="material-icons">local_hospital</i>',
        'Clinique' => '<i class="material-icons">local_hospital</i>',
        'Gare de Cenon' => '<i class="material-icons">directions_transit</i>',
        'Gare de Bordeaux' => '<i class="material-icons">directions_transit</i>',
        'Arrêt de tram' => '<i class="material-icons">directions_transit</i>'
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::LOCATIONS as $location => $category) {
            $departure = new Departure();
            $departure->setName($location);
            $departure->setCategory($category);
            $manager->persist($departure);
            $this->addReference('departure_' . $i, $departure);
            $i++;
        }

        $manager->flush();
    }
}
