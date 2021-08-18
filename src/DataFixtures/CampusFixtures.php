<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Campus;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $campus = Array();
        for($i = 0; $i < count($campus);$i++){
            $campus[$i] = new Campus();
            $campus[$i]->setNomCampus("Nantes");
            $campus[$i]->setNomCampus("Renne");
            $manager->persist($campus[$i]);
        }

        $manager->flush();
    }
}
