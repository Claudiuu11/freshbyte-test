<?php

namespace Drupal\freshbyte\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * This form creates the Disclaimer form.
 */
class DisclaimerForm extends Formbase {

  /**
   * Set the form id.
   */
  public function getFormId() {
    return 'disclaimer_form';
  }

  /**
   * Creates the Disclaimer form fields.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['disclaimer'] = [
      '#type' => 'fieldset',
      '#title' => t('Disclaimer'),
      '#description' => t('You must accept the disclaimer to be able to see the product details!')
    ];

    $form['disclaimer']['accept'] = [
      '#type' => 'checkbox',
      '#title' => t('I accept the disclaimer!'),
    ];


    // Add a submit handler to set the cookie
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $value = $form_state->getValue('accept');
      if( $value == 1) {
        setcookie('disclaimer', 1, time() + (3600 * 24)); 
        $form_state->setRedirect('<front>');
      }else{
        \Drupal::messenger()->addError('You must accept the disclaimer to be able to see the product details!');
        $form_state->setRedirect('<front>');
      }
  }
}