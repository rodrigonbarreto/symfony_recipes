<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipes")
 */
class RecipeController extends AbstractController
{
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RecipeController constructor.
     * @param RecipeRepository $recipeRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager
    ){

        $this->recipeRepository = $recipeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="recipe_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' =>  $this->recipeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $this->entityManager->persist($recipe);
            $this->entityManager->flush();
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET"})
     */
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($recipe);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }
}
