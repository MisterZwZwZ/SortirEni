<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Sorties;
use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\User;
use App\Entity\Villes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class SortiesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $villes = array();
        for($i = 0; $i <= 5; $i++){
            $villes[$i] = new Villes();
            $villes[$i]->setNom($faker->city);
            $villes[$i]->setCodePostal((int) $faker->postcode);
            $manager->persist($villes[$i]);
        }

        $lieux = array();
        for($j = 0; $j <= 8; $j++){
            $lieux[$j] = new Lieux();
            $lieux[$j]->setNom('Lieux n°'.$j);
            $lieux[$j]->setRue($faker->streetName);
            $lieux[$j]->setVille($villes[mt_rand(0,4)]);
            $lieux[$j]->setLatitude($faker->latitude);
            $lieux[$j]->setLongitude($faker->longitude);
            $manager->persist($lieux[$j]);
        }

        $campus = array();
        for ($k = 0; $k < 3; $k++) {
            $campus[$k] = new Campus();
            $campus[$k]->setNomCampus($faker->name);
            $manager->persist($campus[$k]);
        }

        $users = array();
        for ($l = 0; $l <= 12; $l++){
            $users[$l] = new User();
            $users[$l]->setNom($faker->lastName);
            $users[$l]->setPrenom($faker->firstName);
            $users[$l]->setPseudo($faker->userName);
            $users[$l]->setTelephone($faker->phoneNumber);
            $users[$l]->setEmail($faker->email);
            $users[$l]->setPassword($faker->password);
            $users[$l]->setActif(true);
            $users[$l]->setCampusUser($campus[rand(0,2)]);
            $manager->persist($users[$l]);
        }


            $etats = new Etats();
            $etats->setLibelle('Créée');
            $manager->persist($etats);

            $etats2 = new Etats();
            $etats2->setLibelle('Ouverte');
            $manager->persist($etats2);

            $etats3 = new Etats();
            $etats3->setLibelle('Clôturée');
            $manager->persist($etats3);

            $etats4 = new Etats();
            $etats4->setLibelle('Activité en cours');
            $manager->persist($etats4);

            $etats5 = new Etats();
            $etats5->setLibelle('Passée');
            $manager->persist($etats5);

            $etats6 = new Etats();
            $etats6->setLibelle('Annulée');
            $manager->persist($etats6);


            $sorties = Array();
            for($m = 0; $m <=10 ; $m++){
                $sorties[$m] = new Sorties();
                $sorties[$m]->setNom($faker->lastName);
                $sorties[$m]->setDateHeureDebut($faker->dateTime);
                $sorties[$m]->setDateLimiteInscription($faker->dateTime);
                $sorties[$m]->setDuree($faker->numberBetween(30,120));
                $sorties[$m]->setDescription($faker->text);
                $sorties[$m]->setEtatSortie($etats);
                $sorties[$m]->setOrganisateur($users[mt_rand(0,11)]);
                $sorties[$m]->setSiteOrganisateur($campus[rand(0,2)]);
                $sorties[$m]->setNbIncriptionsMax($faker->numberBetween(2,20));
                $sorties[$m]->setLieu($lieux[mt_rand(0,7)]);
                $manager->persist($sorties[$m]);
            }
        $manager->flush();
    }
}
