<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    const VOLUNTEERS = [
        '21',
        '22',
    ];

    const BENEFICIARIES = [
        '23',
        '24'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::BENEFICIARIES as $key => $beneficiaryID) {
            $beneficiary = new User();
            $beneficiary->setMobicoopId($beneficiaryID);
            $beneficiary->setIsActive(1);
            $beneficiary->setStatus('beneficiary');
            $beneficiary->setCreatedAt(new \DateTime('now'));
            $manager->persist($beneficiary);
            $this->addReference('beneficiary_' . $key, $beneficiary);
        }

        foreach (self::VOLUNTEERS as $key => $volunteerID) {
            $volunteer = new User();
            $volunteer->setMobicoopId($volunteerID);
            $volunteer->setIsActive(1);
            $volunteer->setStatus('volunteer');
            $volunteer->setCreatedAt(new \DateTime('now'));
            $manager->persist($volunteer);
            $this->addReference('volunteer_' . $key, $volunteer);
        }

        $manager->flush();
    }
}
