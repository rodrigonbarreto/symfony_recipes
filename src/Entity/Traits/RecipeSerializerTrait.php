<?php


namespace App\Entity\Traits;


trait RecipeSerializerTrait {
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'ingredient' => $this->getIngredient(),
            'portions' => $this->getPortions(),
            'preparationTime' => $this->getPreparationTime(),
            'category' => $this->getCategory(),
        ];
    }
}