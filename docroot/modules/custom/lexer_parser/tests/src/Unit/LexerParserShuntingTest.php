<?php

namespace Drupal\Tests\lexer_parser\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\lexer_parser\LexerParserService;

/**
 * @coversDefaultClass \Drupal\lexer_parser\LexerParserService
 */
class LexerParserShuntingTest extends UnitTestCase {

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
   * @covers ::shunting
   *
   * @dataProvider providerShunting
   *
   * @param array $data
   *   Parsed mathematical expression.
   * @param array $result
   *   The expected result.
   */
  public function testShunting(array $data, array $result) {
    $this->assertSame($result, $this->lexerParserService->shunting($data));
  }

  /**
   * Provides data for testParse.
   *
   * @return array
   *   An array of test data.
   */
  public function providerShunting() {
    return [
      [
        ['1', '+', '1', '+', '1', '-', '1', '-', '1'],
        ['1', '1', '+', '1', '+', '1', '-', '1', '-'],
      ],
      [
        ['8', '/', '2', '/', '2', '/', '2'],
        ['8', '2', '/', '2', '/', '2', '/'],
      ],
      [
        ['256.34', '*', '3', '-', '23', '/', '6'],
        ['256.34', '3', '*', '23', '6', '/', '-'],
      ],
      [
        ['-', '1', '+', '1'], ['1', '-', '1', '+'],
      ],
    ];
  }

}
