<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Task;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un nouvel objet Faker
        $faker = Factory::create('fr_FR');

        // Création de 5 Catégories
        for ($c = 0; $c <5; $c++) {
            $tag = new Tag;

        // Ajout du nom de la catégorie
            $tag->setName($faker->colorName());

        // Faire persister les données
            $manager->persist($tag);
        }

        $manager->flush();

        $listeTags = $manager->getRepository(Tag::class)->findAll();

        // Création entre 15 et 30 tâches aléatoirement
        for ($t= 0; $t < mt_rand(15, 30); $t++) {

            // Création d'un nouvel objet task
            $task = new Task;
            
            // Nourir l'objet créé
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime())
                ->setDueAt($faker->dateTimeBetween('now', '6 months'))
                ->setTag($faker->randomElement(($listeTags)));

            // On fait persister les données
            $manager->persist($task);
        }

        $manager->flush();
    }
}
