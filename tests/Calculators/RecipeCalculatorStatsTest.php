<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Calculators\Filters\CountPerPostcodeAndTimeFilter;
use RecipeCalculator\Calculators\Filters\CountPerRecipeFilter;
use RecipeCalculator\Calculators\Filters\PostcodeMostDeliveredFilter;
use RecipeCalculator\Calculators\Filters\RecipesByIngredientsFilter;
use RecipeCalculator\Calculators\Filters\UniqueRecipeCount;
use RecipeCalculator\Calculators\Filters\UniqueRecipesCountable;
use RecipeCalculator\Calculators\RecipeCalculatorStats;
use RecipeCalculator\Recipes\Recipe;

final class RecipeCalculatorStatsTest extends TestCase
{
    /**
     * @dataProvider recipesDataProvider
     */
    public function testPostcodeMostDeliveredFilter(
        array $recipesDataProvider,
        array $expectedDataProvider
    ) {

        $calculatorStats = new RecipeCalculatorStats;
        $countableUniqueRecipes = new UniqueRecipesCountable();

        $calculatorStats->addFilter(new UniqueRecipeCount($countableUniqueRecipes));
        $calculatorStats->addFilter(new CountPerRecipeFilter($countableUniqueRecipes));
        $calculatorStats->addFilter(new PostcodeMostDeliveredFilter);
        $calculatorStats->addFilter(new CountPerPostcodeAndTimeFilter(
                deliveryPostCode: '6789',
                deliveryFrom: '12PM',
                deliveryTo: '10PM')
        );
        $calculatorStats->addFilter(new RecipesByIngredientsFilter(['Potato', 'Beans']));


        foreach ($recipesDataProvider as $recipe) {
            $recipe = Recipe::fromArray($recipe);
            $calculatorStats->filter($recipe);
        }
        $this->assertEquals($expectedDataProvider, $calculatorStats->getResults());
    }

    public function recipesDataProvider(): array
    {
        $input =  [
            ['recipe' => 'Rice and Beans', 'postcode' => '1111', 'delivery' => 'Wednesday 1AM - 7PM'],
            ['recipe' => 'Rice and Beans', 'postcode' => '1234', 'delivery' => 'Thursday 1PM - 7PM'],
            ['recipe' => 'Stroganoff', 'postcode' => '4321', 'delivery' => 'Monday 10AM - 1PM'],
            ['recipe' => 'Omelet', 'postcode' => '6789', 'delivery' => 'Friday 2AM - 10AM'],
            ['recipe' => 'Mashed Potatoes', 'postcode' => '6789', 'delivery' => 'Friday 4PM - 11PM']
        ];

        $output =                 [
            'unique_recipe_count' => 4,
            'count_per_recipe' => [
                ['recipe' => 'Mashed Potatoes', 'count' => 1],
                ['recipe' => 'Omelet', 'count' => 1],
                ['recipe' => 'Rice and Beans', 'count' => 2],
                ['recipe' => 'Stroganoff', 'count' => 1],
            ],
            'busiest_postcode' => [
                'postcode' => '6789',
                'count' => 2
            ],
            'count_per_postcode_and_time' => [
                'postcode' => '6789',
                'from' => '12PM',
                'to' => '10PM',
                'delivery_count' => 1
            ],
            'match_by_name' => ['Mashed Potatoes', 'Rice and Beans']
        ];

        return [
            [$input, $output]
        ];
    }
}