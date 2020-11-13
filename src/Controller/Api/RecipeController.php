<?php

namespace App\Controller\Api;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * RecipeController constructor.
     * @param RecipeRepository $recipeRepository
     */
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @Route("/", name="api_recipe", methods={"GET"})
     */
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();
        return new JsonResponse(['recipes' => $recipes]);
    }
}
