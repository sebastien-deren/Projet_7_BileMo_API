<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product
            ->setName('G5')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();

        $product
            ->setName('G4')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product
            ->setName('G5')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();

        $product
            ->setName('G4')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);        $product = new Product();
        $product
            ->setName('G3')
            ->setBrand('LG')
            ->setOperatingSystem('Android');
        $manager->persist($product);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
