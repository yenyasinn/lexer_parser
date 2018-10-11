<?php

namespace Drupal\Tests\lexer_parser\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\lexer_parser\LexerParserService;

/**
 * @coversDefaultClass \Drupal\lexer_parser\LexerParserService
 */
class LexerParserComputeTest extends UnitTestCase {

  /**
   * Testing class.
   *
   * @var \Drupal\lexer_parser\LexerParserService
   */
  protected $lexerParserService;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->lexerParserService = new LexerParserService();
  }

  /**
   * @covers ::compute
   *
   * @dataProvider providerCompute
   *
   * @throws \Exception
   *   Thrown if no result of syntax error.
   *
   * @param string $input
   *   String with mathematical expression.
   *
   * @param mixed $result
   *   The expected result.
   */
  public function testCompute($input, $result) {
    $this->assertSame($result, $this->lexerParserService->compute($input));
  }

  /**
   * Provides data for testParse.
   *
   * @return array
   *   An array of test data.
   */
  public function providerCompute() {
    return [
      ['1 + 1 + 1 - 1 - 1', 1],
      ['8 / 2 / 2 / 2', 1],
      ['256.34 * 3 - 23 / 6', 765.18666666667],
      ['-1 + 1', 0],
    ];
  }

  /**
   * @covers ::compute
   *
   * @dataProvider providerDivisionByZero
   *
   * @throws \Exception
   *   Thrown if division by zero.
   *
   * @param string $string
   *   String with mathematical expression.
   */
  public function testDivisionByZero($string) {
    $this->setExpectedException(\Exception::class);
    $this->lexerParserService->compute($string);
  }

  /**
   * Provides data for testDivisionByZero.
   *
   * @return array
   *   An array of test data.
   */
  public function providerDivisionByZero() {
    return [
      ['10 / 0'],
      ['5 + 3 / 0'],
    ];
  }

}
