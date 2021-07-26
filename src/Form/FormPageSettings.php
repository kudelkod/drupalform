<?php

/**
 * @file
 * Contains \Drupal\drupalform\Form\CollectPhoneSettings.
 */

namespace Drupal\drupalform\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class FormPageSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drupal_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'drupalform.drupal_form.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('drupalform.drupal_form.settings');

    $form['Theme'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Default Theme'),
      '#default_value' => $config->get('Theme'),
    );
    $form['Message'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Default Message'),
      '#default_value' => $config->get('Message'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('drupalform.drupal_form.settings')
      ->set('Theme', $values['Theme'])
      ->set('Message', $values['Message'])
      ->save();
  }
}
