<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

class CountPerRecipeFilter implements RecipeFilterContract
{
    protected string $filterName = 'count_per_recipe';

    public function __construct(protected UniqueRecipeCountableContract $recipeCountable)
    {
    }

    public function getFilterName(): string
    {
        return $this->filterName;
    }

    public function filter(Recipe $recipe): void
    {
    }

    public function getResult(): array
    {
        return $this->recipeCountable->getUniqueRecipes();
    }
}
