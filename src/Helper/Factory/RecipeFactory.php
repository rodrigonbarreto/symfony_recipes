<?php


namespace App\Helper\Factory;


use App\Entity\Medico;
use App\Entity\Recipe;
use App\Helper\CustomException\EntityFactoryException;
use App\Repository\CategoryRepository;

class RecipeFactory
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createEntity(string $json) : Recipe
    {
        $objectToJson = json_decode($json);
        $this->checkAllProperties($objectToJson);
        $recipe = new Recipe();
        $this->fillField($recipe, $objectToJson);
        return $recipe;
    }

    public function updateEntity(Recipe $recipe,string $json) : Recipe
    {
        $objectToJson = json_decode($json);
        $this->checkAllProperties($objectToJson);

        $this->fillField($recipe, $objectToJson);
        return $recipe;
    }

    private function checkAllProperties($objectToJson)
    {

        if (!property_exists($objectToJson, 'name')) {

            throw new EntityFactoryException('name is required');
        }
    }

    /**
     * @param Recipe $recipe
     * @param $objectToJson
     */
    public function fillField(Recipe $recipe, $objectToJson): void
    {
        $recipe
            ->setName($objectToJson->name)
            ->setDescription($objectToJson->description)
            ->setIngredient($objectToJson->ingredient)
            ->setPortions($objectToJson->portions)
            ->setPreparationTime($objectToJson->preparationTime);
    }
}