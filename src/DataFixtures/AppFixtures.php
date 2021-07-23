<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Faker
        $faker = Factory::create();

        // Création de 10 formations
        for($i=0; $i<10; $i++){
            $formation = new Formation();

            $formation->setNomFormation($faker->words(3, true))
                ->setDescription($faker->text(300))
                ->setDate($faker->dateTimeBetween('-6 month', 'now'))
                ->setEtat('programmé')
                ->setPrixFormateur(100.00)
                ->setSlug($faker->slug(3));

            $manager->persist($formation);
        }

        $manager->flush();
    }
}
