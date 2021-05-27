<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

interface UniqueRecipeCountableContract
{
    public function addRecipe(Recipe $recipe): void;

    public function getUniqueRecipes(): array;

    public function getUniqueRecipesTotal(): int;
}
