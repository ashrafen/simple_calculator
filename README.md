# Simple calculator

A simple caluclator that can compute simple mathematical operations using the most basic operators `+, -, *, /` and can handle floating point operations (decimal numbers).

The module provides a text field formatter that can output the result after the expression.

In case of parser error the error will be displayed inline near the expression.


## Requirements

* Drupal 8 site.

## Installation

Checkout or download from github, install and enable the module in your site.

```bash
cd modules/custom
git clone git@github.com:ashrafen/simple_calculator.git
drush en simple_calculator
```

## Usage

After enabling the module create a `text field` in your content type and then choose a display of `Simple calculator formatter` as a formatter at `admin/structure/types/manage/CONTENT_TYPE/display`

The formatter will display the expression and on hover the result will be displayed with css animation.

```
6 + 4 * 10 = 46
```

## Unit testing

Run test cases for this module using `phpunit` (tested with phpunit 6.5)

```
cd web
../vendor/bin/phpunit -c core/phpunit.xml.dist modules/custom/simple_calculator/tests/src/Calculator/CalculatorTest.php
```

You should get 10 tests and 10 assertions

