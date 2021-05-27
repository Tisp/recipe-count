<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use RecipeCalculator\Commands\RecipeCalculatorStatsCommand;

$application = new Application();
$application->add(new RecipeCalculatorStatsCommand());
$application->run();
