<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

class UniqueRecipeCount implements RecipeFilterContract
{
    protected string $filterName = 'unique_recipe_count';

    public function getFilterName(): string
    {
        return $this->filterName;
    }

    public function __construct(protected UniqueRecipeCountableContract $recipeCountable)
    {
    }

    public function filter(Recipe $recipe): void
    {
    }

    public function getResult(): int
    {
        return $this->recipeCountable->getUniqueRecipesTotal();
    }
}
