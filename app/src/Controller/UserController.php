<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private TranslatorInterface $translator;

    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/create', name: 'user_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->userService->delete($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );
        }

        return $this->redirectToRoute('user_index');
    }
}