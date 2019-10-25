<?php

namespace Drupal\evolent\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class evolentForm.
 *
 * @package Drupal\evolent\Form
 */
class EvolentForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'evolent_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('evolent', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();

    }

    
    $form['first_name'] = array(
      '#type' => 'textfield',
      '#title' => t('First  Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['first_name']) && $_GET['num']) ? $record['first_name']:'',
      );

    $form['last_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Last  Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['last_name']) && $_GET['num']) ? $record['last_name']:'',
      );

    
    $form['mobile_number'] = array(
      '#type' => 'number',
      '#title' => t('Mobile Number:'),
      '#default_value' => (isset($record['mobile_number']) && $_GET['num']) ? $record['mobile_number']:'',
      );

     $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['email']) && $_GET['num']) ? $record['email']:'',
      );
      $form['status'] = array (
      '#type' => 'select',
      '#title' => ('Status'),
      '#options' => array(
        'active' => t('Active'),
        'inActive' => t('InActive'),        
        ),
      '#default_value' => (isset($record['status']) && $_GET['num']) ? $record['status']:'active',
      );
    
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
      ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

          if (strlen($form_state->getValue('mobile_number')) != 10 ) {
            $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
           }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

        $field=$form_state->getValues();
        $field  = array(
              'first_name'   => $field['first_name'],
              'last_name'   => $field['last_name'],
              'mobile_number' =>  $field['mobile_number'],
              'email' =>  $field['candidate_mail'],
              'status' => $field['status'],
          );
        if (isset($_GET['num'])) {

          $query = \Drupal::database();// Update record
          $query->update('evolent')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('evolent.display_table_controller_display');

       }else
       {
           $query = \Drupal::database();// insert new record
           $query ->insert('evolent')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");
          $form_state->setRedirect('evolent.display_table_controller_display');
       }
       
     }

}
