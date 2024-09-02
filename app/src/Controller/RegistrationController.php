<?php
/**
 * Registration Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param RegistrationService $registrationService RegistrationService
     */
    public function __construct(private readonly RegistrationService $registrationService)
    {
    }

    /**
     * Aciton Register.
     *
     * @param Request $request request
     *
     * @return Response response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/register', name: 'app_register')]
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
