<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $genres = array(
            "Comédie",
            "Drame",
            "Romance",
            "Action",
            "Thriller",
            "Horreur",
            "Science-fiction",
            "Fantastique",
            "Policier",
            "Western",
            "Musique",
            "Aventure",
            "Animation",
            "Documentaire",
            "Biopic",
            "Guerre",
            "Comédie musicale",
            "Erotique",
            "Sport",
            "Film noir",
            "Film d'art"
          );
        foreach($genres as $data){
            $genre = new Genre();
            $genre->setType($data);
            $manager->persist($genre);
        }


        $manager->flush();
    }
}
