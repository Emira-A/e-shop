<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Préparation de Faker
        $faker = Factory::create('fr_FR');

        // ---------- | LES CATEGORIES
        $femme = new Category();
        $femme->SetName('Femme');
        $femme->SetAlias('femme');
        $manager->persist($femme);

        $homme = new Category();
        $homme->SetName('Homme');
        $homme->SetAlias('homme');
        $manager->persist($homme);

        $enfant = new Category();
        $enfant->SetName('Enfant');
        $enfant->SetAlias('enfant');
        $manager->persist($enfant);

        $maison = new Category();
        $maison->SetName('Maison');
        $maison->SetAlias('maison');
        $manager->persist($maison);

        // On sauvegarde le tout dans la BDD
        $manager->flush();

        // ---------- | LES UTILISATEURS

        // Création d'un admin
        $admin = new User();
        $admin->setFirstname('Emira');
        $admin->setLastname('AMAMI');
        $admin->setEmail('emira@eshop.com');
        $admin->setPassword('test');
        $admin->setAdresse('2 rue du Soleil 75020 Paris');
        $admin->setRoles(['ROLE_USER']);
        $manager->persist($admin);

        // Création d'utilisateurs normaux
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword('test');
            $user->setAdresse($faker->address);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        // On sauvegarde le tout dans la BDD
        $manager->flush();


        // Création des produits
        for ($i = 0; $i < 5; $i++) {
            $product = new Product();
            $product->setTitle($faker->sentence());
            $product->setAlias('lorem-ipsum-dolor-este');
            $product->setImage($faker->imageUrl(500,350));
            $product->setReference('1001');
            $product->setDescription($faker->text);
            $product->setPrix($faker->randomFloat(1, 20, 30));
            $product->addCategory($femme);
            $product->setUser($admin);
            $manager->persist($product);

            $product2 = new Product();
            $product2->setTitle($faker->sentence());
            $product2->setAlias('lorem-ipsum-dolor-este');
            $product2->setImage($faker->imageUrl(500,350));
            $product2->setReference('1001');
            $product2->setDescription($faker->text);
            $product2->setPrix($faker->randomFloat(1, 20, 80));
            $product2->addCategory($homme);
            $product2->setUser($admin);
            $manager->persist($product2);

            $product3 = new Product();
            $product3->setTitle($faker->sentence());
            $product3->setAlias('lorem-ipsum-dolor-este');
            $product3->setImage($faker->imageUrl(500,350));
            $product3->setReference('1001');
            $product3->setDescription($faker->text);
            $product3->setPrix($faker->randomFloat(1, 20, 30));
            $product3->addCategory($enfant);
            $product3->setUser($admin);
            $manager->persist($product3);

            $product4 = new Product();
            $product4->setTitle($faker->sentence());
            $product4->setAlias('lorem-ipsum-dolor-este');
            $product4->setImage($faker->imageUrl(500,350));
            $product4->setReference('1001');
            $product4->setDescription($faker->text);
            $product4->setPrix($faker->randomFloat(1, 20, 30));
            $product4->addCategory($maison);
            $product4->setUser($admin);
            $manager->persist($product4);
        }

        $manager->flush();


    }
}
