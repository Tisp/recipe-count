<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Recipes\Recipe;
use RecipeCalculator\Calculators\Filters\PostcodeMostDeliveredFilter;

final class PostcodeMostDeliveredFilterTest extends TestCase
{
    /** @dataProvider recipesDataProvider */
    public function testPostcodeMostDeliveredFilter(array $recipesDataProvider)
    {
        $filter = new PostcodeMostDeliveredFilter();

        foreach ($recipesDataProvider as $recipe) {
            $filter->filter(Recipe::fromArray($recipe));
        }

        $expected = ['postcode' => '6789', 'count' => '2'];
        $this->assertEquals($expected, $filter->getResult());
        $this->assertEquals('busiest_postcode' , $filter->getFilterName());
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