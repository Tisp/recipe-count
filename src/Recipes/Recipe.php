<?php

namespace RecipeCalculator\Recipes;

class Recipe
{

    public function __construct(public string $recipe, public string $postcode, public string $delivery)
    {
    }

    public static function fromArray(array $recipe): Recipe
    {
        return new static(...$recipe);
    }
}
