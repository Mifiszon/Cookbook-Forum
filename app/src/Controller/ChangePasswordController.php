<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'app_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $currentPassword = $form->get('currentPassword')->getData();


            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Nieprawidłowe stare hasło.');
                return $this->redirectToRoute('app_change_password');
            }


            $newPassword = $form->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Hasło zmienione pomyślnie.');
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('change_password/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
