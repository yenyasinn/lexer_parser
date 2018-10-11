<?php

namespace Drupal\Tests\lexer_parser\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\lexer_parser\LexerParserService;

/**
 * @coversDefaultClass \Drupal\lexer_parser\LexerParserService
 */
class LexerParserParseTest extends UnitTestCase {

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
   * @covers ::parse
   *
   * @dataProvider providerStrings
   *
   * @throws \Exception
   *   Thrown if no result of syntax error.
   *
   * @param string $string
   *   String with mathematical expression.
   *
   * @param array $result
   *   The expected parsed array.
   */
  public function testParse($string, array $result) {
    $this->assertSame($result, $this->lexerParserService->parse($string));
  }

  /**
   * Provides data for testParse.
   *
   * @return array
   *   An array of test data.
   */
  public function providerStrings() {
    return [
      ['1 + 1 + 1 - 1 - 1', ['1', '+', '1', '+', '1', '-', '1', '-', '1']],
      ['8 / 2 / 2 / 2', ['8', '/', '2', '/', '2', '/', '2']],
      ['256.34 * 3 - 23 / 6', ['256.34', '*', '3', '-', '23', '/', '6']],
      ['-1 + 1', ['-', '1', '+', '1']],
    ];
  }

  /**
   * @covers ::parse
   *
   * @dataProvider providerSyntaxError
   *
   * @throws \Exception
   *   Thrown if no result of syntax error.
   *
   * @param string $string
   *   String with mathematical expression.
   */
  public function testSyntaxError($string) {
    $this->setExpectedException(\Exception::class);
    $this->lexerParserService->parse($string);
  }

  /**
   * Provides data for testSyntaxError.
   *
   * @return array
   *   An array of test data.
   */
  public function providerSyntaxError() {
    return [
      ['some string'],
      ['1 + d + 1'],
      ['5,12'],
      ['* 8 + 1'],
      ['8+1*'],
    ];
  }

}
