<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\Type\CommentType;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    public function __construct(private CommentServiceInterface $commentService)
    {
    }

    #[Route('/add/{recipeId}', name: 'comment_add', methods: ['GET', 'POST'])]
    public function add(Request $request, int $recipeId): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($recipeId);
            $comment->setAuthor($user);
            $comment->setRecipe($recipe);
            $this->commentService->save($comment);

            $this->addFlash('success', 'message.comment_added_successfully.');

            return $this->redirectToRoute('recipe_details', ['id' => $recipeId]);
        }

        return $this->render('comment/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
