<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(UserRepository $repository, EntityManagerInterface $manager) {

        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/user/listing", name="user_listing")
     */
    public function index(): Response
    {   
        // Récupérer les infos de l'utilisateur connecté
        $user = $this->getUser();
        //dd($user);

        // Dans le repo, on récupère les entrées
        $users = $this->repository->findAll();
        
        // Affichage dans le var_dumper
        // dd($users);

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/create", name="user_create")
     * @Route("/user/update/{id}", name="user_update", requirements={"id" = "\d+"})
     */
    public function user(User $user = null, Request $request) {
        
        if (!$user) {
            $user = new User;
        }

        $form = $this->createForm(UserType::class, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('user_listing');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

    /**
     *
     * @Route("user/delete/{id}", name="user_delete", requirements={"id" = "\d+"})
     */
    public function deleteUser(User $user): Response {

        $this->manager->remove($user);
        $this->manager->flush();

        return $this->redirectToRoute('user_listing');
    }
}