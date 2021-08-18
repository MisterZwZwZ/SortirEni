<?php

namespace App\DataFixtures;

use App\Entity\Sorties;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class SortiesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //création de 10 sorties
        $faker = Faker\Factory::create('fr_FR');
            $sorties = Array();
            for($i = 0; $i <10 ; $i++){
                $sorties[$i] = new Sorties();
                $sorties[$i]->setNom($faker->lastName);
                $sorties[$i]->setDateHeureDebut($faker->dateTime);
                $sorties[$i]->setDateLimiteInscription($sorties[$i]->getDateHeureDebut()->modify('+1 month'));
                $sorties[$i]->setDuree($faker->numberBetween(0,90));
                $sorties[$i]->setDescription($faker->text);
                $sorties[$i]->setEtat($faker->lastName);
                $sorties[$i]->setIdOrganisateur(1);
                $sorties[$i]->setNbIncriptionsMax($faker->numberBetween(0,20));
                $manager->persist($sorties[$i]);
            }

        $manager->flush();
    }
}
