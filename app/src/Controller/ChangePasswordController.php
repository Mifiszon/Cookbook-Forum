<?php
/**
 * ChangePassword Controller.
 */

namespace App\Controller;

use App\Form\Type\ChangePasswordFormType;
use App\Service\ChangePasswordServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ChangePasswordController.
 */
class ChangePasswordController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ChangePasswordServiceInterface $changePasswordService
     */
    public function __construct(private readonly ChangePasswordServiceInterface $changePasswordService)
    {
    }

    /**
     * Action change password.
     *
     * @param Request $request Request.
     *
     * @return Response response.
     */
    #[Route('/change-password', name: 'app_change_password')]
    #[IsGranted('ROLE_USER')]
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            try {
                $this->changePasswordService->changePassword($user, $currentPassword, $newPassword);
                $this->addFlash('success', 'Hasło zmienione pomyślnie.');

                return $this->redirectToRoute('recipe_index');
            } catch (Exception $e) {
                $this->addFlash('error', 'Nieprawidłowe stare hasło.');

                return $this->redirectToRoute('app_change_password');
            }
        }

        return $this->render('change_password/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
