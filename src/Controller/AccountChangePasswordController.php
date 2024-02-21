<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountChangePasswordController extends AbstractController
{
    #[Route('/mon-compte/changer-mon-mot-de-passe', name: 'app_account_change_password')]
    public function index(Request $request, UserPasswordHasherInterface $encode, EntityManagerInterface $manager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pass = $form->get('old_password')->getData();
            if ($encode->isPasswordValid($user, $old_pass)) {
                $new_pass = $form->get('new_password')->getData();
                $new_pass_hashed = $encode->hashPassword($user, $new_pass);
                $user->setPassword($new_pass_hashed);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'La mise à jour de votre mot de passe a bien été prise en compte');

                return $this->redirectToRoute('app_account');
            }
            // dd($old_pass);
        }

        return $this->render('account/change_password.html.twig', [
            'formChangePassword' => $form
        ]);
    }
}
