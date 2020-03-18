<?php

namespace Drupal\webform_gigya_consent\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provide settings form for Gigya API credentials.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'webform_gigya_consent.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_gigya_consent_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['gigya_server'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gigya Server'),
      '#default_value' => $config->get('gigya_server'),
    ];

    $form['gigya_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gigya Client ID'),
      '#default_value' => $config->get('gigya_client_id'),
    ];

    $form['gigya_secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gigya Secret Key'),
      '#default_value' => $config->get('gigya_secret_key'),
    ];

    $form['gigya_scope'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gigya Scope'),
      '#default_value' => $config->get('gigya_scope'),
    ];

    $form['source_app_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source Application Name'),
      '#default_value' => $config->get('source_app_name'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('gigya_server', $form_state->getValue('gigya_server'))
      ->set('gigya_client_id', $form_state->getValue('gigya_client_id'))
      ->set('gigya_secret_key', $form_state->getValue('gigya_secret_key'))
      ->set('gigya_scope', $form_state->getValue('gigya_scope'))
      ->set('source_app_name', $form_state->getValue('source_app_name'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
