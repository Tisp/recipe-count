<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Calculators\Filters\RecipesByIngredientsFilter;
use RecipeCalculator\Recipes\Recipe;

final class RecipesByIngredientsFilterTest extends TestCase
{

    /** @dataProvider recipesDataProvider */
    public function testPostcodeMostDeliveredFilter(array $recipesDataProvider)
    {
        $filter = new RecipesByIngredientsFilter(['Potato', 'Beans']);

        foreach ($recipesDataProvider as $recipe) {
            $filter->filter(Recipe::fromArray($recipe));
        }

        $expected = ['Mashed Potatoes', 'Rice and Beans'];
        $this->assertEquals($expected, $filter->getResult());
        $this->assertEquals('match_by_name' , $filter->getFilterName());
    }

    public function testEmptyConstructorNeedToThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Recipes By Ingredients Filter must have at least one ingredient');
        new RecipesByIngredientsFilter([]);
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