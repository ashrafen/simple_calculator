<?php

namespace Drupal\simple_calculator\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\simple_calculator\Calculator;

/**
 * Plugin implementation of the 'simple_calculator_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "simple_calculator_formatter",
 *   label = @Translation("Simple calculator formatter"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *   }
 * )
 */
class SimpleCalculatorFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  use MessengerTrait;
  /**
   * Constructs a StringFormatter instance.
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
   *   Any third party settings.
   * @param \Drupal\simple_calculator\Calculator $calculator
   *   Calculator class.
   */
  public function __construct(
      $plugin_id,
      $plugin_definition,
      FieldDefinitionInterface $field_definition,
      array $settings,
      $label, $view_mode,
      array $third_party_settings,
      Calculator $calculator
    ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->calculator = $calculator;
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
      $container->get('simple_calculator.calculator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'simple_calculator',
        '#type' => 'container',
        '#attributes' => [
          'class' => ['formula-container'],
        ],
        '#attached' => [
          'library' => [
            'simple_calculator/show_result',
          ],
        ],
        '#result' => $this->viewValue($item),
        '#formula' => $item->value,
      ];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    try {
      $postfix = $this->calculator->parser($item->value);
      $result = $this->calculator->evaluate($postfix);
    }
    catch (\Exception $e) {
      $this->messenger()->addError(t($e->getMessage()));
      $result = $e->getMessage();
    }
    return nl2br(Html::escape($result));
  }
}
