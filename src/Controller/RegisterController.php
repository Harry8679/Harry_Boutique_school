<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encode, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $password_hashed = $encode->hashPassword($user, $user->getPassword());
            $user->setPassword($password_hashed);
            // dd($user);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Félicitation, votre compte a bien été créé. Veuillez vous connecter.');

            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('register/register.html.twig', [
            'formRegister' => $form
        ]);
    }
}
