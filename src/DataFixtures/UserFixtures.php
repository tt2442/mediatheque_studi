<?php

namespace App\DataFixtures;

use App\Entity\User;
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


        $manager->flush();
    }
}
