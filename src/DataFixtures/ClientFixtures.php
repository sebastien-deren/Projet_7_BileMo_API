<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    public function __construct( private UserPasswordHasherInterface $clientPasswordHasher ){}
    public function load(ObjectManager $manager): void
    {
        $clients=[];

        $client = new \App\Entity\Client();
        $client->setUsername("green");
        $client->setRoles(["ROLE_USER"]);
        $client->setPassword($this->clientPasswordHasher->hashPassword($client, "password"));
        $manager->persist($client);
        $clients[]=$client;

        // Création d'un user admin
        $clientAdmin = new \App\Entity\Client();
        $clientAdmin->setUsername("seb");
        $clientAdmin->setRoles(["ROLE_ADMIN"]);
        $clientAdmin->setPassword($this->clientPasswordHasher->hashPassword($clientAdmin, "password"));
        $manager->persist($clientAdmin);
        $clients[]=$clientAdmin;

        $faker = Factory::create();
        for($i=0;$i<20;$i++){
            $user = new User();
            $user->setName($faker->name());
            $user->setFirstName($faker->firstName());
            $user->setEmail($faker->email());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->addClient($clients[rand(0,count($clients)-1)]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
