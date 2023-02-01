<?php

namespace App\DataFixtures;

use App\Entity\Acteur;
use App\Entity\Realisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50 ;$i++){
            $acteur = new Acteur();
            $acteur->setPrenom($faker->firstName);
            $acteur->setNom($faker->lastName);
            $manager->persist($acteur);

        }
        
        for ($i = 0; $i < 5 ;$i++){
            $realisateur = new Realisateur();
            $realisateur->setPrenom($faker->firstName);
            $realisateur->setNom($faker->lastName);
            $manager->persist($realisateur);

        }

        $manager->flush();
    }
}
