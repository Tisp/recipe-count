<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use RecipeCalculator\Recipes\Recipe;

class PostcodeMostDeliveredFilter implements RecipeFilterContract
{
    protected string $filterName = 'busiest_postcode';
    protected array $postCodes = [];

    public function getFilterName(): string
    {
        return $this->filterName;
    }

    public function filter(Recipe $recipe): void
    {
        $this->postCodes[$recipe->postcode] = isset($this->postCodes[$recipe->postcode])
            ? $this->postCodes[$recipe->postcode] + 1
            : 1;
    }

    public function getResult(): array
    {
        asort($this->postCodes);
        $mostDeliveredPostCode = array_key_last($this->postCodes);

        return [
            'postcode' => $mostDeliveredPostCode,
            'count' => $this->postCodes[$mostDeliveredPostCode]
        ];
    }
}
