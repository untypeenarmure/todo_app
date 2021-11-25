<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        // Création d'un nouvel objet Faker
        $faker = Factory::create('fr_FR');

        // Création d'utilisateurs

        for($u = 0; $u < 5; $u++) {

            // Création d'un objet User
            $user = new User;

            // Hashage du mdp avec les paramètres de sécurité de $user
            // Dans config\packages\security.yaml
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setPassword($hash);
            
            // Si Premier user créé, rôle = admin
            if($u === 0) {
                $user->setRoles(["ROLE_ADMIN"])
                ->setEmail("admin@admin.fr");
            } else {
                $user->setEmail($faker->safeEmail());
            }
            
            $manager->persist($user);
        }

        for ($s = 1; $s<4; $s++){
            $todo = new Status;
            $todo->setLabel($s);
            $manager->persist($todo);
        }

        $manager->flush();

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
        $listeUsers = $manager->getRepository(User::class)->findAll();
        $listeStatus = $manager->getRepository(Status::class)->findAll();

        // Création entre 15 et 30 tâches aléatoirement
        for ($t= 0; $t < mt_rand(15, 30); $t++) {

            // Création d'un nouvel objet task
            $task = new Task;
            
            // Nourir l'objet créé
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime())
                ->setDueAt($faker->dateTimeBetween('now', '6 months'))
                ->setTag($faker->randomElement(($listeTags)))
                ->setUser($faker->randomElement($listeUsers))
                ->setStatus($faker->randomElement($listeStatus))
                ->setIsArchived(0);
            // On fait persister les données
            $manager->persist($task);

        }

        $manager->flush();

    }   
}
