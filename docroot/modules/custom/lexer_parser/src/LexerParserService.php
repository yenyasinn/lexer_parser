<?php

namespace Drupal\lexer_parser;

/**
 * @file
 * Mathematical Lexer & Parser service.
 */

/**
 * Lexer & Parser service.
 */
class LexerParserService {

  /**
   * List of characters that are allowed.
   *
   * @var string
   */
  protected $pattern = '/^([\+\-\*\/]|\d*\.\d+|\d+\.\d*|\d+|[ \t]+)/';

  /**
   * High priority operators.
   *
   * @var array
   */
  protected $priorityHigh = ['*', '/'];

  /**
   * Operators that are calculated after high prioritized.
   *
   * @var array
   */
  protected $priorityNormal = ['+', '-'];

  /**
   * Compute mathematical expression.
   */
  public function compute($input) {
    $data = $this->parse($input);
    $queue = $this->shunting($data);
    return $this->calculate($queue);
  }

  /**
   * Parse input string.
   *
   * @var string $input
   *   Input string.
   *
   * @throws \Exception
   *   Thrown if no result of syntax error.
   *
   * @return array
   *   List of numbers and operators
   */
  protected function parse($input) {
    while (trim($input) !== '') {
      if (!preg_match($this->pattern, $input, $match)) {
        // Syntax error.
        throw new \Exception('Syntax error');
      }

      if (empty($match[1]) && $match[1] !== '0') {
        // Nothing found.
        throw new \Exception('Mathematical expression has not been found');
      }

      // Remove the first matched token from the input, for the next iteration.
      $input = substr($input, strlen($match[1]));

      // Get the value of the matched token.
      $value = trim($match[1]);

      // Ignore whitespace matches.
      if ($value === '') {
        continue;
      }

      $result[] = $value;
    }

    return $result;

  }

  /**
   * Implements Shunting-yard algorithm.
   *
   * Https://en.wikipedia.org/wiki/Shunting-yard_algorithm.
   */
  protected function shunting($data) {
    $stack = [];
    $queue = [];

    foreach ($data as $value) {
      if (is_numeric($value)) {
        $queue[] = $value;
        continue;
      }

      // Operator.
      $check_operator = TRUE;
      if (empty($stack)) {
        // It is first value.
        $stack[] = $value;
      }
      else {
        while ($check_operator) {
          $top_operator = array_pop($stack);

          if (!empty($top_operator) && $this->checkPriority($top_operator, $value)) {
            $queue[] = $top_operator;
          }
          else {
            if (!empty($top_operator)) {
              $stack[] = $top_operator;
            }
            $stack[] = $value;
            $check_operator = FALSE;
          }
        }
      }
    }

    if (!empty($stack)) {
      do {
        $queue[] = array_pop($stack);
      } while (!empty($stack));
    }

    return $queue;
  }

  /**
   * Checks what operator is more prioritized.
   */
  protected function checkPriority($operator_1, $operator_2) {
    return $this->getPriority($operator_1) > $this->getPriority($operator_2);
  }

  /**
   * Gets priority of operator.
   */
  protected function getPriority($val) {
    if (in_array($val, $this->priority_high)) {
      return 1;
    }

    return 0;
  }

  /**
   * Postfix evaluation algorithm. Calculates result.
   *
   * Https://en.wikipedia.org/wiki/Reverse_Polish_notation.
   *
   * @var array $queue
   *   Array that is prepared by Shunting-yard algorithm.
   *
   * @throws \Exception
   *   Thrown when division on zero happens.
   *
   * @return int
   *   Result of calculation.
   */
  protected function calculate($queue) {
    $stack = [];
    foreach ($queue as $value) {
      if (is_numeric($value)) {
        $stack[] = $value;
        continue;
      }

      // Operator.
      $val2 = array_pop($stack);
      $val1 = array_pop($stack);
      $result = $this->executeOperator($val1, $val2, $value);

      $stack[] = $result;
    }

    return array_pop($stack);
  }

  /**
   * Executes mathematical operation.
   */
  protected function executeOperator($val1, $val2, $operator) {
    switch ($operator) {
      case '+':
        $result = $val1 + $val2;
        break;

      case '-':
        $result = $val1 - $val2;
        break;

      case '*':
        $result = $val1 * $val2;
        break;

      case '/':
        if ($val2) {
          $result = $val1 / $val2;
        }
        else {
          throw new \Exception('Division on zero');
        }
    }

    return $result;
  }

}
