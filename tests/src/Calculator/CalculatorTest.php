<?php
namespace Drupal\Tests\simple_calculator\Calculator;

use Drupal\simple_calculator\Calculator;
use Drupal\Tests\UnitTestCase;

/**
 * Simple test to ensure that asserts pass.
 *
 * @group simple_calculator
 */
class CalculatorTest extends UnitTestCase {

  /**
   * Before a test method is run, setUp() is invoked.
   * Create the required objects.
   */
  public function setUp() {
    $this->Calculator = new Calculator();
  }

  /**
   * @covers Drupal\simple_calculator\Calculator::parser
   * @dataProvider parserDataProvider
   */
  public function testParser($expression, $expectedPostfix) {
    $result = $this->Calculator->parser($expression);
    $this->assertEquals($result, $expectedPostfix);
  }

  public function parserDataProvider() {
    return [
      ['5 + 8','5 8 +'],
      ['(2 + 3) * 5','2 3 + 5 *'],
      ['((15 / (7 - (1 + 1))) * 3) - (2 + (1 + 1))','15 7 1 1 + - / 3 * 2 1 1 + + -'],
      ['1.1 + 2','1.1 2 +'],
      ['(1+2.1) * 33 + 200 / 10 +  100','1 2.1 + 33 * 200 10 / + 100 +'],
    ];
  }

  /**
   * @covers Drupal\simple_calculator\Calculator::evaluate
   * @dataProvider evaluateDataProvider
   */
  public function testEvaluate($postfix, $expectedResult) {
    $result = $this->Calculator->evaluate($postfix);
    $this->assertEquals($result, $expectedResult);
  }

  public function evaluateDataProvider() {
    return [
      ['5 8 +','13'],
      ['2 3 + 5 *','25'],
      ['15 7 1 1 + - / 3 * 2 1 1 + + -','5'],
      ['1.1 2 +','3.1'],
      ['1 2.1 + 33 * 200 10 / + 100 +','222.3'],
    ];
  }

  /**
   * Once test method has finished running, whether it succeeded or failed, tearDown() will be invoked.
   * Unset the objects created on setUp.
   */
  public function tearDown() {
    unset($this->Calculator);
  }
}
