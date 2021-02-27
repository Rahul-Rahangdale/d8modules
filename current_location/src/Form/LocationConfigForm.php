<?php

namespace Drupal\current_location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the form for storing the Timezone and related configurations.
 */
class LocationConfigForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'current_location.config';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "location_config_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get ekyc config object.
    $config = $this->config(self::SETTINGS);

    // City field.
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t("City"),
      '#default_value' => $config->get('city'),
      '#description' => $this->t("Please enter your city name."),
    ];

    // Country field.
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Country"),
      '#default_value' => $config->get('country'),
      '#description' => $this->t("Please enter your country name."),
    ];

    // Timezone field.
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t("Timezone"),
      '#options' => [
        'America/Chicago' => 'America/Chicago',
        'America/New_York' => 'America/New_York',
        'Asia/Tokyo' => 'Asia/Tokyo',
        'Asia/Dubai' => 'Asia/Dubai',
        'Asia/Kolkata' => 'Asia/Kolkata',
        'Europe/Amsterdam' => 'Europe/Amsterdam',
        'Europe/Oslo' => 'Europe/Oslo',
        'Europe/London' => 'Europe/London',
      ],
      '#default_value' => $config->get('timezone'),
      '#description' => $this->t("Please select your timezone."),
      '#empty_option' => $this->t('--Please Select--'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get ekyc config object.
    $config = $this->config(self::SETTINGS);

    // Get form values.
    $values = $form_state->getValues();

    // Set configurations and save.
    $config->set('city', $values['city']);
    $config->set('country', $values['country']);
    $config->set('timezone', $values['timezone']);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
