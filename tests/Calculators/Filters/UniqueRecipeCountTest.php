<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Calculators\Filters\RecipeFilterContract;
use RecipeCalculator\Calculators\Filters\UniqueRecipeCount;
use RecipeCalculator\Calculators\Filters\UniqueRecipesCountable;
use RecipeCalculator\Recipes\Recipe;

class UniqueRecipeCountTest extends TestCase
{
    protected array $recipes = [
        ['recipe' => 'Rice and Beans', 'postcode' => '1111', 'delivery' => 'Wednesday 1AM - 7PM'],
        ['recipe' => 'Rice and Beans', 'postcode' => '1234', 'delivery' => 'Thursday 1PM - 7PM'],
        ['recipe' => 'Stroganoff', 'postcode' => '4321', 'delivery' => 'Monday 10AM - 1PM'],
        ['recipe' => 'Omelet', 'postcode' => '6789', 'delivery' => 'Friday 2AM - 10AM'],
        ['recipe' => 'Mashed Potatoes', 'postcode' => '6789', 'delivery' => 'Friday 4PM - 11PM'],
    ];

    protected RecipeFilterContract $filter;

    protected function setUp(): void
    {
        parent::setUp();


    }
    /** @dataProvider recipesDataProvider */
    public function testPostcodeMostDeliveredFilter(array $recipesDataProvider)
    {
        $countRecipeFilter = new UniqueRecipesCountable();

        foreach ($recipesDataProvider as $recipe) {
            $countRecipeFilter->addRecipe(Recipe::fromArray($recipe));
        }

        $filter = new UniqueRecipeCount($countRecipeFilter);

        $this->assertEquals(4, $filter->getResult());
        $this->assertEquals('unique_recipe_count' , $filter->getFilterName());
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