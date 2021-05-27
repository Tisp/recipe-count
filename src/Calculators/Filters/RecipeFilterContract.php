<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

interface RecipeFilterContract
{
    public function getFilterName(): string;

    public function filter(Recipe $recipe): void;

    public function getResult(): array|int;
}
