<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\Stage;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Creation générateur de données
        $faker = \Faker\Factory::create("fr_FR");

        //Nombre de donnees de test à créer pour chaque entité
        $nbDonneesTest = 50;

        /////////////////////////////////
        ///////////FORMATIONS////////////
        /////////////////////////////////
        $DU = new Formation();
        $DU->setNomCourt("DU TIC");
        $DU->setNomLong("Diplôme Universitaire Technologies de l'Information et de la Communication");
        $manager->persist($DU);

        $DUT = new Formation();
        $DUT->setNomCourt("DUT Info");
        $DUT->setNomLong("Diplôme Universitaire Technologique Informatique");
        $manager->persist($DUT);

        $LP = new Formation();
        $LP->setNomCourt("LP Prog Avancée ");
        $LP->setNomLong("Licence Professionnelle Programmation Avancée");
        $manager->persist($LP);


        for ($i=0; $i <= $nbDonneesTest; $i++) {

          /////////////////////////////////
          /////////////STAGES//////////////
          /////////////////////////////////
          $stage = new Stage();
          $stage->setIntitule($faker->text($maxNbChars = 20));
          $stage->setContact($faker->email);

          //Generation de 3 paragraphes au hasard et concaténation dans une variable qu'on passera dans missions
          $miss = $faker->paragraphs($nb = 3, $asText = false);
          $texte ="";
          foreach ($miss as $value) {
            $texte=$texte.$value;
          }
          $stage->setMissions($texte);

          //Génération d'une valeur entre 1 et 3 pour définir au hasard la formation concernée par le stage
          $num = $faker->numberBetween($min = 1, $max = 3);

          if ($num == 1) {
            $laFormation = $DU;
          }
          elseif ($num == 2) {
              $laFormation = $LP;
            }
            else {
              $laFormation = $DUT;
            }

          $stage->addFormation($laFormation);
          $stage->addFormation($DU);
          $manager->persist($stage);

          /////////////////////////////////
          ///////////ENTREPRISES///////////
          /////////////////////////////////
          $entreprise = new Entreprise();
          $entreprise->setNom($faker->company);
          $entreprise->setActivite($faker->catchPhrase);
          $entreprise->setAdresse($faker->address);
          $entreprise->setSite($faker->domainName);
          $entreprise->addStage($stage);
          $manager->persist($entreprise);
        }

        $manager->flush();
    }
}
