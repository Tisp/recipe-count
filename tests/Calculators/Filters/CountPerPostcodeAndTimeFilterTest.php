<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RecipeCalculator\Calculators\Filters\CountPerPostcodeAndTimeFilter;
use RecipeCalculator\Recipes\Recipe;

final class CountPerPostcodeAndTimeFilterTest extends TestCase
{
    /** @dataProvider recipesDataProvider */
    public function testCountPerPostcodeAndTimeFilterResult(array $recipesDataProvider)
    {
        $filter = new CountPerPostcodeAndTimeFilter(
            deliveryPostCode: '6789',
            deliveryFrom: '12PM',
            deliveryTo: '10PM'
        );

        foreach ($recipesDataProvider as $recipe) {
            $filter->filter(Recipe::fromArray($recipe));
        }

        $expected = [
            'postcode' => '6789',
            'from' => '12PM',
            'to' => '10PM',
            'delivery_count' => 1
        ];

        $this->assertEquals($expected, $filter->getResult());
        $this->assertEquals('count_per_postcode_and_time' , $filter->getFilterName());
    }

    public function testWrongTimeFormatConstructorNeedToThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid time format');
        new CountPerPostcodeAndTimeFilter('1234', '15PM', '20PM');
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
