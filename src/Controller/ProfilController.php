<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        //dd($user);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route ("/profil/update", name="profil_update")
     */
    public function updateProfil(Request $request){

        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class, $user, []);

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
}