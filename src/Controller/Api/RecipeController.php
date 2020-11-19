<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Helper\Factory\RecipeFactory;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/api/recipes")
 */
class RecipeController extends AbstractController
{
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var RecipeFactory
     */
    private $recipeFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RecipeController constructor.
     * @param RecipeRepository $recipeRepository
     * @param RecipeFactory $recipeFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RecipeRepository $recipeRepository, RecipeFactory $recipeFactory, EntityManagerInterface $entityManager)
    {
        $this->recipeRepository = $recipeRepository;
        $this->recipeFactory = $recipeFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="api_recipe", methods={"GET"})
     */
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();
        return $this->json($recipes);
    }

    /**
     * @Route("/{id}", name="api_recipe_show", methods={"GET"})
     */
    public function show(Recipe $recipe): Response
    {
        return $this->json($recipe);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(name="api_recipe_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $recipe = $this->recipeFactory->createEntity($request->getContent());
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        return $this->json($recipe, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     * @Route("/{id}", name="recipe_api_update", methods={"PUT","PATCH"})
     */
    public function update(int $id, Request $request, Recipe $recipe): Response
    {
        $recipe = $this->recipeFactory->updateEntity( $recipe, $request->getContent());
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json($recipe);
    }

    /**
     * @param int $id
     * @param Recipe $recipe
     * @return Response
     * @Route("/{id}", name="recipe_api_delete", methods={"PUT","DELETE"})
     */
    public function delete(Recipe $recipe): Response
    {
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
        return $this->json($recipe);
    }

}
