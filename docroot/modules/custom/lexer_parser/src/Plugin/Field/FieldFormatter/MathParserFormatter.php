<?php

namespace Drupal\lexer_parser\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\lexer_parser\LexerParserService;

/**
 * Plugin implementation of the 'lexer_parser_math_parser' formatter.
 *
 * @FieldFormatter(
 *   id = "lexer_parser_math_parser",
 *   label = @Translation("Mathematical parser"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *     "string",
 *     "string_long"
 *   }
 * )
 */
class MathParserFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $lexer_parser_servive = new LexerParserService();
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = strip_tags($item->value);
      $lexer_parser_servive->compute($value);

      $elements[$delta] = [
        '#markup' => $value,
      ];
    }

    return $elements;
  }

}
