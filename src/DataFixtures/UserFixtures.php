<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->seed(2);

        $user = new User();
        $user->setEmail('administrateur@mediatheque.com')
            ->setRoles(['ROLE_Administrateur', 'ROLE_Employe'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'p;P73VWOP>b$CY+R/~^B'))
            ->setNom('Dupuis')
            ->setPrenom('Paul')
            ->setDatebirth(new \DateTime())
            ->setAdresse('Paris 18ème')
            ->setActive(true);
        $manager->persist($user);

        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $users[$i] = new User();
            $users[$i]->setEmail($faker->email)
                ->setRoles(['ROLE_Habitant'])
                ->setPassword($this->passwordEncoder->encodePassword($users[$i], 'password'))
                ->setNom($faker->lastname)
                ->setPrenom($faker->firstname)
                ->setDatebirth($faker->dateTime)
                ->setAdresse($faker->address)
                ->setActive(false);
            $manager->persist($users[$i]);
        }

        $genres = [];
        for ($i = 0; $i < 25; $i++) {
            $genres[$i] = new Genre();
            $genres[$i]->setTitre($faker->word);
            $manager->persist($genres[$i]);
        }


        $livres = [];
        for ($i = 0; $i < 300; $i++) {
            $livres[$i] = new Livre();
            $livres[$i]->setType('Romans')
                ->addGenre($genres[$faker->numberBetween($min = 1, $max = 24)])
                ->addGenre($genres[$faker->numberBetween($min = 1, $max = 24)])
                ->setDisponible(true)
                ->setReserve(false)
                ->setTitre($faker->sentence($nbWords = 3, $variableNbWords = true))
                ->setDate($faker->dateTime)
                ->setDescription($faker->sentence($nbWords = 30, $variableNbWords = true))
                ->setAuteur($faker->lastname . " " . $faker->firstname);
            $manager->persist($livres[$i]);
        }


        $manager->flush();
    }
}
