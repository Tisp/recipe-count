<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Calculators\Filters\CountPerRecipeFilter;
use RecipeCalculator\Calculators\Filters\UniqueRecipesCountable;
use RecipeCalculator\Recipes\Recipe;

final class CountPerRecipeFilterTest extends TestCase
{
    /** @dataProvider recipesDataProvider */
    public function testCountPerRecipeFilterResult(array $recipesDataProvider)
    {
        $uniqueRecipes = new UniqueRecipesCountable();

        foreach ($recipesDataProvider as $recipe) {
            $uniqueRecipes->addRecipe(Recipe::fromArray($recipe));
        }

        $filter = new CountPerRecipeFilter($uniqueRecipes);

        $expected = [
            ['recipe' => 'Rice and Beans', 'count' => '2'],
            ['recipe' => 'Stroganoff', 'count' => '1'],
            ['recipe' => 'Omelet', 'count' => '1'],
            ['recipe' => 'Mashed Potatoes', 'count' => '1'],
        ];

        usort($expected, function ($firstItem, $secondItem) {
            return strcmp($firstItem['recipe'], $secondItem['recipe']);
        });

        $this->assertEquals($expected, $filter->getResult());
        $this->assertEquals('count_per_recipe' , $filter->getFilterName());
    }

    public function recipesDataProvider(): array
    {
        return [
            [[
                ['recipe' => 'Rice and Beans', 'postcode' => '1111', 'delivery' => 'Wednesday 1AM - 7PM'],
                ['recipe' => 'Rice and Beans', 'postcode' => '1234', 'delivery' => 'Thursday 1PM - 7PM'],
                ['recipe' => 'Stroganoff', 'postcode' => '4321', 'delivery' => 'Monday 10AM - 1PM'],
                ['recipe' => 'Omelet', 'postcode' => '6789', 'delivery' => 'Friday 2AM - 10AM'],
                ['recipe' => 'Mashed Potatoes', 'postcode' => '6789', 'delivery' => 'Friday 4PM - 11PM']
            ]]
        ];
    }
}

