<?php

namespace Drupal\tejas_term_filter\Element;

use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form element for term filter.
 *
 * Shows the Filter type and Term selection fields.
 * Term selection is only shown when the filter type chosen is "none".
 *
 * Properties:
 * - #vid: The vocabulary id of which the terms to be loaded.
 *
 * Usage example:
 * @code
 * $form['term_filter'] = [
 *   '#type' => 'term_filter',
 *   '#vid' => 'my_vocabulary',
 *   //'#default_value' => 'include',
 *   '#default_value' => [
 *     'filter_options' => 'include',
 *   ],
 *   '#title' => $this->t('Filter by term'),
 *   '#description' => $this->t('Optionally select a term and whether it should be included or excluded in the result set'),
 * ];
 * @endcode
 *
 * @FormElement("term_filter")
 */
class TermFilter extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#theme_wrappers' => ['form_element'],
      '#input' => TRUE,
      '#tree' => TRUE,
      '#process' => [
        [get_class($this), 'termFilterFields'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function termFilterFields(&$element, FormStateInterface $form_state, &$complete_form) {
    self::addFilterField($element);
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  protected static function addFilterField(array &$element) {
    $element['filter_options'] = [
      '#type' => 'radios',
      '#title' => t('Options'),
      '#attributes' => ['name' => ['filter_options_class']],
      '#options' => [
        'none' => 'None', 
        'include' => 'Include', 
        'exclude' => 'Exclude',
      ],
      '#default_value' => isset($element['#default_value']['filter_options']) ? $element['#default_value']['filter_options'] : 'none',
    ];

    $vid = $element['#vid'];
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $term_data[0] = "--Select Any--";
    foreach ($terms as $term) {
      $term_data[$term->tid] = $term->name;
    }
    asort($term_data);

    $element['filter_terms'] = [
      '#type' => 'select',
      '#title' => t('Terms'),
      '#options' => $term_data,
      '#default_value' => isset($element['#default_value']['filter_terms']) ? $element['#default_value']['filter_terms'] : 0,
      '#states' => [
        'invisible' => [
          ':input[name="filter_options_class"]' => ['value' => 'none'],
        ],
      ],
    ];

    $element['#tree'] = TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input === FALSE) {
      $element += [
        '#default_value' => [],
      ];
      return $element['#default_value'] + [
        'filter_options' => '',
        'filter_terms' => '',
      ];
    }

    $value = [
      'filter_options' => '',
      'filter_terms' => '',
    ];

    // Throw out all invalid array keys; we only allow pass1 and pass2.
    foreach ($value as $allowed_key => $default) {

      // These should be strings, but allow other scalars since they might be
      // valid input in programmatic form submissions. Any nested array values
      // are ignored.
      if (isset($input[$allowed_key]) && is_scalar($input[$allowed_key])) {
        $value[$allowed_key] = (string) $input[$allowed_key];
      }
    }
    return $value;
  }

}
