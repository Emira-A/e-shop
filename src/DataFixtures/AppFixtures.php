<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setTitle('product ' . $i);
            $product->setAlias('product');
            $product->setImage('');
            $product->setReference('1001');
            $product->setDescription('lorem ipsum');
            $product->setPrix(mt_rand(10, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
