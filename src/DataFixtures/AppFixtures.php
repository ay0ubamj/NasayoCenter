<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Formation;
use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTimeImmutable;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Faker
        $faker = Factory::create();

        // Création d'un user
        $user = new User();
        $date = new DateTimeImmutable();

        $user->setEmail('test@test.com')
            ->setNom($faker->firstName())
            ->setPrenom($faker->lastName())
            ->setCreatedAt($date);

        $password = $this->encoder->encodePassword($user, '123456789');
        $user->setPassword($password);

        $manager->persist($user);


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
            // Création de 5 commentaires
            for($j=0; $j<5; $j++){
                $commentaire = new Commentaire();

                $commentaire->setCommentaire($faker->text())
                    ->setCreatedAt($date)
                    ->setEtat($faker->randomElement([true, false]))
                    ->setUser($user)
                    ->setFormation($formation);

                $manager->persist($commentaire);
            }

        }

        $manager->flush();
    }
}
