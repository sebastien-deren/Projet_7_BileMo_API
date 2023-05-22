<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use http\Client;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    public function __construct( private UserPasswordHasherInterface $clientPasswordHasher ){}
    public function load(ObjectManager $manager): void
    {

        $user = new \App\Entity\Client();
        $user->setUsername("green");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->clientPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        // Création d'un user admin
        $userAdmin = new \App\Entity\Client();
        $userAdmin->setUsername("seb");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->clientPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
