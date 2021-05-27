<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators;

use RecipeCalculator\Calculators\Filters\RecipeFilterContract;
use RecipeCalculator\Recipes\Recipe;

class RecipeCalculatorStats
{
    protected array $filters = [];

    public function addFilter(RecipeFilterContract $filter)
    {
        $this->filters[] = $filter;
    }

    public function filter(Recipe $recipe): void
    {
        foreach ($this->filters as $filter) {
            $filter->filter($recipe);
        }
    }

    public function getResults(): array
    {
        $results = [];
        foreach ($this->filters as $filter) {
            $results[$filter->getFilterName()] = $filter->getResult();
        }

        return $results;
    }
}
