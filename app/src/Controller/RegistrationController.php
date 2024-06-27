<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    private RegistrationService $registrationService;

    /**
     * @param RegistrationService $registrationService
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->registrationService->register($user);
            $this->addFlash('success', 'Rejestracja przebiegła pomyślnie.');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
