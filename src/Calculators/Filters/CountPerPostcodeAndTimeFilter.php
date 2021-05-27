<?php

declare(strict_types=1);

namespace RecipeCalculator\Calculators\Filters;

use DateTime;
use InvalidArgumentException;
use RecipeCalculator\Recipes\Recipe;

class CountPerPostcodeAndTimeFilter implements RecipeFilterContract
{
    protected const TIME_FORMAT = 'gA';
    protected string $filterName = 'count_per_postcode_and_time';
    protected DateTime|bool $deliveryFrom;
    protected DateTime|bool $deliveryTo;
    protected int $deliveryCount = 0;

    public function __construct(
        protected string $deliveryPostCode,
        string $deliveryFrom,
        string $deliveryTo
    ) {
        $this->deliveryFrom = DateTime::createFromFormat(self::TIME_FORMAT, $deliveryFrom);
        $this->deliveryTo = DateTime::createFromFormat(self::TIME_FORMAT, $deliveryTo);

        if (!$this->deliveryFrom || !$this->deliveryTo) {
            throw new InvalidArgumentException('Invalid time format');
        }
    }

    public function getFilterName(): string
    {
        return $this->filterName;
    }

    public function filter(Recipe $recipe): void
    {
        if (
            $this->deliveryPostCode === $recipe->postcode
            && ($recipeDeliveryTime = $this->getTimeFromRecipeDelivery($recipe->delivery))
        ) {
            [$to, $from] = $recipeDeliveryTime;
            $recipeDeliveryTo = DateTime::createFromFormat(self::TIME_FORMAT, $to);
            $recipeDeliveryFrom = DateTime::createFromFormat(self::TIME_FORMAT, $from);

            if ($recipeDeliveryFrom >= $this->deliveryFrom && $recipeDeliveryTo <= $this->deliveryTo) {
                $this->deliveryCount++;
            }
        }
    }

    public function getResult(): array
    {
        return [
            'postcode' => $this->deliveryPostCode,
            'from' => $this->deliveryFrom->format(self::TIME_FORMAT),
            'to' => $this->deliveryTo->format(self::TIME_FORMAT),
            'delivery_count' => $this->deliveryCount
        ];
    }

    protected function getTimeFromRecipeDelivery(string $delivery): array|false
    {
        if (preg_match_all('/(1[0-2]|0?[1-9])(AM|PM)/', $delivery, $matches)) {
            return $matches[0];
        }

        return false;
    }
}
