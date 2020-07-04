<?php

namespace App\DataFixtures;

use App\Entity\Arrival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArrivalFixtures extends Fixture
{
    const LOCATIONS = [
        'CADA' => '<i class="material-icons">location_city</i>',
        'Préfecture' => '<i class="material-icons">location_city</i>',
        'CAF' => '<i class="material-icons">location_city</i>',
        'Mairie' => '<i class="material-icons">location_city</i>',
        'Hôpital' => '<i class="material-icons">local_hospital</i>',
        'Médecin' => '<i class="material-icons">local_hospital</i>',
        'Clinique' => '<i class="material-icons">local_hospital</i>',
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::LOCATIONS as $location => $category) {
            $arrival = new Arrival();
            $arrival->setName($location);
            $arrival->setCategory($category);
            $manager->persist($arrival);
            $this->addReference('arrival_' . $i, $arrival);
            $i++;
        }

        $manager->flush();
    }
}
