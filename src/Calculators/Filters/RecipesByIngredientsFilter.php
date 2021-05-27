<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use InvalidArgumentException;
use RecipeCalculator\Recipes\Recipe;

class RecipesByIngredientsFilter implements RecipeFilterContract
{
    protected string $filterName = 'match_by_name';
    protected array $recipes = [];

    public function __construct(protected array $ingredients)
    {
        if (empty($this->ingredients)) {
            throw new InvalidArgumentException('Recipes By Ingredients Filter must have at least one ingredient');
        }
    }

    public function getFilterName(): string
    {
        return $this->filterName;
    }

    public function filter(Recipe $recipe): void
    {
        $this->recipes[$recipe->recipe] = $recipe->recipe;
    }

    public function getResult(): array
    {
        $recipeWithIngredient = [];
        foreach ($this->ingredients as $ingredient) {
            foreach ($this->recipes as $recipe) {
                if (strpos(strtolower($recipe), strtolower($ingredient))) {
                    $recipeWithIngredient[] = $recipe;
                }
            }
        }

        sort($recipeWithIngredient);
        return $recipeWithIngredient;
    }
}
