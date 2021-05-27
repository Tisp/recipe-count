<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

class UniqueRecipesCountable implements UniqueRecipeCountableContract
{
    protected array $recipes = [];
    protected static array $resultCache = [];

    public function addRecipe(Recipe $recipe): void
    {
        if (isset($this->recipes[$recipe->recipe])) {
            $this->recipes[$recipe->recipe]['count']++;
            return;
        }

        $this->recipes[$recipe->recipe] = ['recipe' => $recipe->recipe, 'count' => 1];
    }

    public function getUniqueRecipes(): array
    {
        if (!empty(self::$resultCache)) {
            return self::$resultCache;
        }

        ksort($this->recipes);
        self::$resultCache = array_values($this->recipes);

        return self::$resultCache;
    }

    public function getUniqueRecipesTotal(): int
    {
        return count($this->getUniqueRecipes());
    }
}
