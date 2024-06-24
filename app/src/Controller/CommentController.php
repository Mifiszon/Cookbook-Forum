<?php
/**
* Comment Controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\Type\CommentType;
use App\Repository\RecipeRepository;
use App\Service\CommentServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
* Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    public function __construct(
        private readonly CommentServiceInterface $commentService,
        private readonly TranslatorInterface $translator,
        private readonly RecipeRepository $recipeRepository,)
    {}

    #[Route('/add/{recipeId}', name: 'comment_add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Request $request, int $recipeId): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $recipe = $this->recipeRepository->find($recipeId);
            $comment->setAuthor($user);
            $comment->setRecipe($recipe);
            $this->commentService->add($comment);

            $this->addFlash('success', $this->translator->trans('message.comment_added_successfully'));

            return $this->redirectToRoute('recipe_show', ['id' => $recipeId]);
        }

        return $this->render('comment/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'comment_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', $this->translator->trans('message.comment_deleted_successfully'));

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
