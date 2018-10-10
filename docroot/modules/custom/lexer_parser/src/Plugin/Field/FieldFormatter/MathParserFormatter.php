<?php

namespace Drupal\lexer_parser\Plugin\Field\FieldFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Template\Attribute;
use Drupal\lexer_parser\LexerParserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
class MathParserFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * Lexer & Parser service.
   *
   * @var \Drupal\lexer_parser\LexerParserService
   */
  protected $lexerParser;

  /**
   * Constructs a new DateTimeDefaultFormatter.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Third party settings.
   * @param \Drupal\lexer_parser\LexerParserService $lexer_parser
   *   Lexer & Parser service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, LexerParserService $lexer_parser) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->lexerParser = $lexer_parser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('lexer_paxer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $attributes = new Attribute();

    foreach ($items as $delta => $item) {
      $value = strip_tags($item->value);
      try {
        $result = $this->lexerParser->compute($value);
      }
      catch (\Exception $e) {
        $result = $e->getMessage();
        $attributes->setAttribute('class', 'form-item--error-message');
      }

      $elements[$delta] = [
        '#theme' => 'lexer_parser_math_parser',
        '#expression' => $value,
        '#result' => $result,
        '#attributes' => $attributes,
        '#attached' => [
          'library' => ['lexer_parser/math_parser'],
        ],
      ];
    }

    return $elements;
  }

}
