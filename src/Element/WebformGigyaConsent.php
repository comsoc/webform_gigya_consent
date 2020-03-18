<?php

namespace Drupal\webform_gigya_consent\Element;

use Drupal\Core\Render\Element\Checkbox;

/**
 * Provides a 'webform_gigya_consent' form element.
 *
 * @FormElement("webform_gigya_consent")
 */
class WebformGigyaConsent extends Checkbox {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#return_value' => TRUE,
      '#email_field' => '',
      '#link' => '',
      '#consent_name' => '',
    ] + parent::getInfo();
  }

  /**
   * {@inheritdoc}
   */
  public static function preRenderCheckbox($element) {
    $element = parent::preRenderCheckbox($element);

    // Set a default title.
    if (empty($element['#title'])) {
      $element['#title'] = (string) t('I have read and agree to the privacy policy.');
    }

    // Replace curly brackets with optional link.
    $element['#title'] = str_replace('{', '<a target="_BLANK" href="' . $element['#link'] . '">', $element['#title']);
    $element['#title'] = str_replace('}', '</a>', $element['#title']);

    // Change #type to checkbox so that element is rendered correctly.
    $element['#type'] = 'checkbox';

    return $element;
  }

}
