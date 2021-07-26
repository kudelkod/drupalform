<?php

namespace Drupal\drupalform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Configure example settings for this site.
*/
class FormPage extends FormBase {
/**
* {@inheritdoc}
*/
public function getFormId() {
return 'drupal_form';
}

/**
* {@inheritdoc}
*/
public function buildForm(array $form, FormStateInterface $form_state) {


  $config = \Drupal::config('drupalform.drupal_form.settings');


$form['FirstName'] = array(
'#type' => 'textfield',
'#title' =>$this->t('First Name'),
);

$form['LastName'] = array(
  '#type' => 'textfield',
  '#title' =>$this->t('Last Name'),
);

$form['Theme'] = array(
  '#type' => 'textfield',
  // просто строку.
  '#title' => $this->t('Your Theme'),
  '#default_value' => $config->get('Theme')
);

  $form['Message'] = array(
    '#type' => 'textfield',
    '#title' => $this->t('Your Message'),
    '#default_value' => $config->get('Message')
  );

$form['Email']= array(
  '#type' => 'textfield',
  '#title' =>$this->t('Email'),
);

  $form['actions']['#type'] = 'actions';
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => $this->t('Send'),
    '#button_type' => 'primary',
  );

return $form;
}

public function validateForm(array &$form, FormStateInterface $form_state) {

  if (!strpos($form_state->getValue('Email'), "@")
    || (!strpos($form_state->getValue('Email'), "."))
    || (empty($form_state->getValue('Email'))))
  {
    $form_state->setErrorByName('Email', $this->t('Wrong Email'));
  }
}
  /**
   * Отправка формы.
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    drupal_set_message($this->t('Email sent to address @Email', array(
      '@Email' => $form_state->getValue('Email'),
    )));

    $this->logger('drupal_form')->notice("Theme:"." ".
    $form_state->getValue('Theme').";"." "."To".": "." ".$form_state->getValue('Email'));

    $email = $form_state->getValue('Email');
    $firstname = $form_state->getValue('FirstName');
    $lastname = $form_state->getValue('LastName');
    $hapikey = "eu1-6ba5-b802-4120-889c-dc0cfd77111b";

    $url = "https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/".$email."/?hapikey=".$hapikey;

    $data = array(
      'properties' => [
        [
          'property' => 'FirstName',
          'value' => $firstname
        ],
        [
          'property' => 'LastName',
          'value' => $lastname
        ],
        ['property' => 'Email',
          'value' => $email],
      ]
    );


    $json = json_encode($data,true);

    $response = \Drupal::httpClient()->post($url.'&_format=hal_json', [
      'headers' => [
        'Content-Type' => 'application/json'
      ],
      'body' => $json
    ]);

  }

}
