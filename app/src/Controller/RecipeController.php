<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Dto\RecipeListInputFiltersDto;
use App\Entity\Rating;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\Type\RatingType;
use App\Form\Type\RecipeType;
use App\Resolver\RecipeListInputFiltersDtoResolver;
use App\Service\CommentServiceInterface;
use App\Service\RecipeServiceInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RecipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService Recipe service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(
        private readonly RecipeServiceInterface $recipeService,
        private readonly TranslatorInterface $translator,
        private readonly CommentServiceInterface $commentService,
    ) {
    }

    /**
     * Index action.
     *
     * @param RecipeListInputFiltersDto $filters Input filters
     * @param int                       $page    Page number
     *
     * @return Response HTTP response
     * @throws ORMException
     */
    #[Route(
        name: 'recipe_index',
        methods: 'GET'
    )]
    public function index(
        #[MapQueryString(resolver: RecipeListInputFiltersDtoResolver::class)] RecipeListInputFiltersDto $filters,
        #[MapQueryParameter] int $page = 1
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->recipeService->getPaginatedList(
            $page,
            $user,
            $filters
        );

        $recipes = $pagination->getItems();
        $averageRatings = $this->recipeService->getAverageRatings($recipes);

        return $this->render('recipe/index.html.twig', [
            'pagination' => $pagination,
            'averageRatings' => $averageRatings,
        ]);
    }

    /**
     * Show action.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'recipe_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Recipe $recipe): Response
    {
        $ratings = $this->recipeService->getRatingsForRecipe($recipe);
        $commentsPagination = $this->commentService->getPaginatedCommentsForRecipe($recipe->getId(), 1);

        $rating = new Rating();
        $ratingForm = $this->createForm(RatingType::class, $rating);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'ratings' => $ratings,
            'commentsPagination' => $commentsPagination,
            'ratingForm' => $ratingForm->createView(),
        ]);
    }

    /**
     * Rate action
     *
     * @param Recipe $recipe
     * @param Request $request
     * @return Response
     */
    #[Route('/{id}/rate', name: 'recipe_rate', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function rate(Recipe $recipe, Request $request): Response
    {
        $rating = new Rating();
        $rating->setRecipe($recipe);
        $rating->setUser($this->getUser());

        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->addRating($rating);

            $this->addFlash('success', $this->translator->trans('message.added_successfully'));

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/rate.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'recipe_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $recipe = new Recipe();
        $recipe->setAuthor($user);

        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            ['action' => $this->generateUrl('recipe_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setContent($form->get('content')->getData());

            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'recipe')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setContent($form->get('content')->getData());

            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/edit.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'recipe')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(
            FormType::class,
            $recipe,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }
}
