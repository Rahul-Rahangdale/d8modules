<?php

namespace Drupal\expose_page_contents\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Class to extend the site information form.
 */
class ExtendedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('siteapikey.configuration');
    $form = parent::buildForm($form, $form_state);

    // Add a form element to capture the site key.
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => $this->t("Custom field to set the API Key"),
    ];

    // Change the button text to Update Configuration.
    $form['actions']['submit']['#value'] = $this->t('Update configuration');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get stored site key.
    $stored_site_key = $this->config('siteapikey.configuration')->get('siteapikey');
    // Get value of site key in the form.
    $site_key = $form_state->getValue('siteapikey');
    // Set the value of the key to new one and show message if the old key and,
    // the new keys are differnt.
    if ($stored_site_key !== $site_key) {
      // Store the key given key.
      $this->configFactory()->getEditable('siteapikey.configuration')
        ->set('siteapikey', $site_key)
        ->save();
      // Show the confirmation message.
      $this->messenger()->addStatus("Site API Key has been saved with the value " . $site_key);
    }
    parent::submitForm($form, $form_state);
  }

}
