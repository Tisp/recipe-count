<?php

declare(strict_types=1);

namespace RecipeCalculator\Commands;

use RecipeCalculator\Calculators\Filters\UniqueRecipeCount;
use RecipeCalculator\Calculators\Filters\UniqueRecipesCountable;
use RecipeCalculator\Calculators\RecipeCalculatorStats;
use RecipeCalculator\Recipes\Recipe;
use RecipeCalculator\Calculators\Filters\CountPerPostcodeAndTimeFilter;
use RecipeCalculator\Calculators\Filters\PostcodeMostDeliveredFilter;
use RecipeCalculator\Calculators\Filters\RecipesByIngredientsFilter;
use RecipeCalculator\Calculators\Filters\CountPerRecipeFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use RecipeCalculator\JsonParser;

final class RecipeCalculatorStatsCommand extends Command
{
    private const DEFAULT_POSTCODE = '10120';
    private const DEFAULT_DELIVERY_FROM = '10AM';
    private const DEFAULT_DELIVERY_TO = '3PM';
    private const DEFAULT_RECIPE_INGREDIENTS = ['Potato', 'Veggie', 'Mushroom'];

    protected function configure(): void
    {
        $this
        ->setName('recipe-calculator')
        ->setDescription('Recipes Stats Calculator')
        ->addOption('postcode', 'p', InputOption::VALUE_OPTIONAL, 'Postcode to count deliveries', self::DEFAULT_POSTCODE)
        ->addOption('delivery-from', 'f', InputOption::VALUE_OPTIONAL, 'The start time for postcode delivery search', self::DEFAULT_DELIVERY_FROM)
        ->addOption('delivery-to', 't', InputOption::VALUE_OPTIONAL, 'The final time for postcode delivery search', self::DEFAULT_DELIVERY_TO)
        ->addOption('recipe-ingredients', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Ingredient to search (Case insensitive)', self::DEFAULT_RECIPE_INGREDIENTS)
        ->addOption('pretty-json', 'j', InputOption::VALUE_NONE, 'Pretty print json output');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $postcode = $input->getOption('postcode');
        $deliveryFrom = $input->getOption('delivery-from');
        $deliveryTo = $input->getOption('delivery-to');
        $recipeIngredients = $input->getOption('recipe-ingredients');
        $prettyJson = $input->getOption('pretty-json') ? JSON_PRETTY_PRINT : 0;

        $recipeCalculatorStats = new RecipeCalculatorStats();
        $uniqueRecipeCountable = new UniqueRecipesCountable();

        $recipeCalculatorStats->addFilter(new UniqueRecipeCount($uniqueRecipeCountable));
        $recipeCalculatorStats->addFilter(new CountPerRecipeFilter($uniqueRecipeCountable));
        $recipeCalculatorStats->addFilter(new PostcodeMostDeliveredFilter());
        $recipeCalculatorStats->addFilter(new CountPerPostcodeAndTimeFilter($postcode, $deliveryFrom, $deliveryTo));
        $recipeCalculatorStats->addFilter(new RecipesByIngredientsFilter($recipeIngredients));

        try {
            $recipes = JsonParser::readFromFile('php://stdin');

            foreach ($recipes as $recipe) {
                $recipe = Recipe::fromArray($recipe);
                $uniqueRecipeCountable->addRecipe($recipe);
                $recipeCalculatorStats->filter($recipe);
            }

            $output->write(json_encode($recipeCalculatorStats->getResults(), $prettyJson));

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->write($e->getMessage());
            return Command::FAILURE;
        }
    }
}
