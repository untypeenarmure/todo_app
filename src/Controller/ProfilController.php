<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function updateProfil(){
        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class, $user, []);
    }
}
