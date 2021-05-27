Recipe Stats Calculator
====

This repository contains the solution of [Recipe Stats Calculator](./ASSIGNMENT.md) assignment.

#### Project Notes

* The project was developed to read a file in json format from `stdin`.
  The option behind `stdin` is because the whole project
  was built using docker and using `stdin` I could avoid mounting
  directory between docker and local machine to read the input files.
* The input file should be the same format present in the description of the assignment but can be a minified version, and the keys positions don't matter.
* To increase the performance the application read chucks of size `8192` bytes from the file and tries to split these chunks into several json structures that contain recipes and their values
* All requirements (filters) are separable and independent. You can easily insert a new filter or remove an old one.


Installation and Running
-----

### Dependencies
 - [Docker](https://www.docker.com/)
 - [PHP 8](https://www.php.net/releases/8.0/en.php)
 - [PHP Composer](https://getcomposer.org/)
 - [PHPUnit](https://phpunit.de/)
 - [Make](https://www.gnu.org/software/make/manual/make.html)

### Installation

#### Docker
1 . Run make command to build the container

```shell
make 
```

Or build a docker image:
````shell
docker build -t <CONTAINER-TAG>
````

#### Linux/Mac

1. Install dependencies 
```shell
composer install 
```

Running
====
### Docker
```shell
./bin/recipe-calculator < path-to-fixtures
```
If you built your own container image:
```shell
docker run --rm -i <CONTAINER-TAG> < path-to-fixtures
```

### Linux/Mac
```shell
php ./bin/recipe-stats-calculator.php < path-to-fixtures
```

### Options

| Option      | Usage | Description  | Default Value
| :---:        |    :----:   |     :---:     | :---:
| Postcode       | -p, --postcode       | Postcode to count deliveries   | 10120
| Delivery From    | -f, --delivery-from        |  The start time for postcode delivery search | 10M
| Delivery To  | t, --delivery-to | The final time for postcode delivery search | 3PM
| Recipe Ingredients |  -r, --recipe-ingredients | Recipes ingredients to search (case insensitive) | Potato,Veggie,Mushroom
| Pretty Json | j, --pretty-json | Print pretty  json output |
| Tests | -x, --tests | Run application tests
| Help | -h, --help | Prints help

### Examples
1 . Run with functional requirements
```shell
./bin/recipe-calculator < path-to-fixtures 
```

2 . Count the number of deliveries to postcode `1234` that lie within the delivery time between `12PM` and `15PM` and list the recipe names that contains: `Rice`,  `Beans`, `Cassava`
````shell
./bin/recipe-calculator -p 1234 -f 12PM -t 3PM -r Rice -r Beans -r Cassava < path-to-fixtures
````

### Tests
All tests were written using [PHPUnit](https://phpunit.de/)
```shell
make test
PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

..........                                                        10 / 10 (100%)

Time: 00:00.170, Memory: 18.00 MB

OK (10 tests, 18 assertions)

 Summary:
  Classes: 63.64% (7/11)
  Methods: 84.21% (32/38)
  Lines:   73.44% (94/128)

RecipeCalculator\Calculators\Filters\CountPerPostcodeAndTimeFilter
  Methods:  80.00% ( 4/ 5)   Lines:  95.45% ( 21/ 22)
RecipeCalculator\Calculators\Filters\CountPerRecipeFilter
  Methods: 100.00% ( 4/ 4)   Lines: 100.00% (  5/  5)
RecipeCalculator\Calculators\Filters\PostcodeMostDeliveredFilter
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  9/  9)
RecipeCalculator\Calculators\Filters\RecipesByIngredientsFilter
  Methods: 100.00% ( 4/ 4)   Lines: 100.00% ( 14/ 14)
RecipeCalculator\Calculators\Filters\UniqueRecipeCount
  Methods: 100.00% ( 4/ 4)   Lines: 100.00% (  5/  5)
RecipeCalculator\Calculators\Filters\UniqueRecipesCountable
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 11/ 11)
RecipeCalculator\Calculators\RecipeCalculatorStats
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  9/  9)
RecipeCalculator\FileReader
  Methods:  66.67% ( 2/ 3)   Lines:  85.71% (  6/  7)
RecipeCalculator\JsonParser
  Methods:  60.00% ( 3/ 5)   Lines:  84.62% ( 11/ 13)
RecipeCalculator\Recipes\Recipe
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  3/  3)
```

If you built your own container image:
```shell
docker run --rm -it --entrypoint '/app/vendor/bin/phpunit' <CONTAINER-TAG>
```