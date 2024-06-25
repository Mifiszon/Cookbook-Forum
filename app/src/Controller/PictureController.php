<?php
/**
 * Picture controller.
 */

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Recipe;
use App\Form\Type\PictureType;
use App\Service\PictureServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PictureController.
 */
#[Route('/picture')]
class PictureController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param PictureServiceInterface $pictureService Picture service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(
        private readonly PictureServiceInterface $pictureService,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create/{id}',
        name: 'picture_create',
        methods: 'GET|POST'
    )]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, Recipe $recipe): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $recipe->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access Denied');
        }

        if ($recipe->getPicture()) {
            return $this->redirectToRoute(
                'picture_edit',
                ['id' => $recipe->getPicture()->getId()]
            );
        }

        $picture = new Picture();
        $form = $this->createForm(
            PictureType::class,
            $picture,
            ['action' => $this->generateUrl('picture_create', ['id' => $recipe->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->pictureService->create(
                $file,
                $picture,
                $recipe
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.added_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'picture/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Picture $picture  Picture entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'picture_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Picture $picture): Response
    {
        $recipe = $picture->getRecipe();

        if (!$this->isGranted('ROLE_ADMIN') && $recipe->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access Denied');
        }

        $form = $this->createForm(
            PictureType::class,
            $picture,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('picture_edit', ['id' => $picture->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->pictureService->update(
                $file,
                $picture,
                $recipe
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'picture/edit.html.twig',
            [
                'form' => $form->createView(),
                'picture' => $picture,
            ]
        );
    }
}
