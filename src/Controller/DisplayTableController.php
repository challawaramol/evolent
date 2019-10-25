<?php

namespace Drupal\evolent\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\evolent\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    $build = [
      'description' => [
        '#theme' => 'evolent_description',
        '#description' => 'All contact person info',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    //create table header
    $header_table = array(
      'id'=>    t('SrNo'),
      'first_name' => t('First Name'),
      'last_name' => t('Last Name'),
      'mobile_number' => t('MobileN umber'),
      'email'=>t('Email'),
      'status' => t('Status'),
      'opt' => t('Delete'),
      'opt1' => t('Edit'),
    );

    //select records from table
    $query = \Drupal::database()->select('evolent', 'm');
    $query->fields('m', ['id','first_name','last_name','mobile_number','email','status']);
      // Limit the rows to 5 for each page.
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                        ->limit(5);
      $results = $pager->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/evolent/form/delete/'.$data->id);
        $edit   = Url::fromUserInput('/evolent/contact/form?num='.$data->id);
        //print the data from table
       $rows[] = array(
      'id' =>$data->id,
          'first_name' => $data->first_name,
           'last_name' => $data->last_name,
          'mobile_number' => $data->mobile_number,
          'email' => $data->email,
          'status' => ucfirst($data->status),
           \Drupal::l('Delete', $delete),
           \Drupal::l('Edit', $edit),
      );

    }
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No Contact result found'),
        ];

     // Finally add the pager.
    $form['pager'] = array(
      '#type' => 'pager'
    );   
        return $form;

  }

}
