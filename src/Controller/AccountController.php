<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/mon-compte/profil', name: 'app_account_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        
        return $this->render('account/profile.html.twig', [
            'formProfile' => $form
        ]);
    }
}
