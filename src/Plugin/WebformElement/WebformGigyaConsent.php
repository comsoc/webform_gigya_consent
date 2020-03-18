<?php

namespace Drupal\webform_gigya_consent\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\BooleanBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'webform_gigya_consent' webform element.
 *
 * @WebformElement(
 *   id = "webform_gigya_consent",
 *   label = @Translation("Webform Gigya Consent"),
 *   description = @Translation("Provides a webform checkbox element which tracks user consent with Gigya's Consent API."),
 *   category = @Translation("Gigya"),
 * )
 */
class WebformGigyaConsent extends BooleanBase {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    $properties = [
      'title' => $this->t('I have read and agree to the privacy policy.'),
      'title_display' => 'after',
      'email_field' => '',
      'link' => '',
      'consent_name' => '',
    ] + parent::getDefaultProperties();

    unset($properties['unique'],
      $properties['unique_entity'],
      $properties['unique_user'],
      $properties['unique_error'],
      $properties['icheck'],
      $properties['field_prefix'],
      $properties['field_suffix'],
      $properties['description'],
      $properties['description_display'],
      $properties['title_display']);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function initialize(array &$element) {
    // Set default #title.
    if (empty($element['#title'])) {
      $element['#title'] = $this->getDefaultProperty('title');
    }

    // Backup #title and remove curly brackets.
    // Curly brackets are used to add link to #title when it is rendered.
    $element['#_webform_gigya_consent_title'] = $element['#title'];
    $element['#title'] = str_replace(['{', '}'], ['', ''], $element['#title']);

    parent::initialize($element);
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    // Restore #title with curly brackets.
    if (isset($element['#_webform_gigya_consent_title'])) {
      $element['#title'] = $element['#_webform_gigya_consent_title'];
      unset($element['#_webform_gigya_consent_title']);
    }

    parent::prepare($element, $webform_submission);
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Get the webform's fields and make a list of options out of them.
    $webform = $form_state->getFormObject()->getWebform();
    $webformFields = $webform->getElementsInitializedAndFlattened();
    $fields = ['' => $this->t('- Select a field -')];
    foreach ($webformFields as $field_id => $field) {
      if ($field['#type'] !== 'webform_gigya_consent') {
        $fields[$field_id] = $field['#title'] . ' (' . $field['#type'] . ')';
      }
    }

    $form['element']['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
    ];

    $form['webform_gigya_consent'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Gigya Consent Settings'),
    ];

    $form['webform_gigya_consent']['email_field'] = [
      '#type' => 'select',
      '#options' => $fields,
      '#title' => $this->t('Email Field'),
      '#required' => TRUE,
      '#description' => $this->t("Select the Webform field where the email address of the user giving consent is stored."),
    ];

    $form['webform_gigya_consent']['consent_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gigya Consent Name'),
      '#required' => TRUE,
      '#description' => $this->t("Provide the Gigya consent name."),
    ];

    return $form;
  }

}
