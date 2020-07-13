<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    const BENEFICIARIES = [
        0 => '23',
        1 => '24'
    ];

    const VOLUNTEERS = [
        0 => '21',
        1 => '22',
    ];

    const ADMIN = 39;

    public function load(ObjectManager $manager)
    {
        foreach (self::BENEFICIARIES as $key => $beneficiaryID) {
            $beneficiary = new User();
            $beneficiary->setMobicoopId($beneficiaryID);
            $beneficiary->setIsActive(1);
            $beneficiary->setStatus('beneficiary');
            $beneficiary->setRoles(array('ROLE_USER_BENEFICIARY'));
            $manager->persist($beneficiary);
            $this->addReference('beneficiary_' . $key, $beneficiary);
        }

        foreach (self::VOLUNTEERS as $key => $volunteerID) {
            $volunteer = new User();
            $volunteer->setMobicoopId($volunteerID);
            $volunteer->setIsActive(1);
            $volunteer->setStatus('volunteer');
            $volunteer->setRoles(array('ROLE_USER_VOLUNTEER'));
            $manager->persist($volunteer);
            $this->addReference('volunteer_' . $key, $volunteer);
        }

        $admin = new User();
        $admin->setMobicoopId(self::ADMIN);
        $admin->getIsActive(1);
        $admin->setStatus('admin');
        $admin->setRoles(array('ROLE_ADMIN'));
        $manager->persist($admin);

        $manager->flush();
    }
}
