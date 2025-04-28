<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\Categories; // Add this line to import the Categories class
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LivresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($j = 1; $j <= 5; $j++) {
            $categorie = new Categories();
            $names = ['Roman', 'Science Fiction', 'Histoire', 'Biographie', 'Fantasy'];
            $categorie->setLibelle($names[$j - 1]);
            $categorie->setSlug(str_replace(' ', '-', $names[$j - 1]));
            $categorie->setDescription($faker->text);
            $manager->persist($categorie);

            for ($i = 1; $i <= random_int(10, 15); $i++) {
                $livre = new Livre();
                $titre = $faker->name();
                $livre->setTitre($titre);
                $livre->setISBN($faker->isbn13);
                $livre->setSlug($faker->slug);
                $livre->setImage("https://tuniscope.com/uploads/images/content/samir-wafi-20052021-v.jpg");
                $livre->setResume($faker->text);
                $livre->setEditeur($faker->company);
                $livre->setDateEdition($faker->dateTime);
                $livre->setPrix($faker->randomFloat(2, 0, 100));
                $livre->setCategorie($categorie);
                $manager->persist($livre);
            }
        }
        $manager->flush();
    }
}