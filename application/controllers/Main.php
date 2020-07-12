<?php

header('charset=utf-8');
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Main extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->model('model_main', '', TRUE);
  }
/**
 * To create a graph with txt file 
 * @return type
 */
  public function create_graph() {

    $array = [];
    $lineas = file("./uploads/input.txt");
    $i = 0;
    foreach ($lineas as $linea_num => $linea) {

      $datos = explode(",", $linea);
      $array[$i]['start'] = $datos[0];
      $array[$i]['end'] = $datos[1];
      $i ++;
    }
    return $array;
  }
  
/**
 * To verify if a graph has a cycle
 * @return boolean
 */
  public function get_cycle() {
    $search = 'A';
    $array = $this->create_graph();
    $end = '';
    $start_flag = false;
    $end_flag = false;

    echo "To search " . $search . '<br>';
    for ($i = 0; $i < count($array); $i++) {

      $start_flag = $this->is_present($array, 'start', $search);
      $end = $this->is_present($array, 'start', $search, 1);
      $end_flag = $this->is_present($array, 'end', $search);
      if ($start_flag && $end_flag) {
        return $this->verify_way($array, $i, $end, $search);
      } else {
        echo 'The graph does not has a cycle for item ' . $search;
        return false;
      }
    }
  }
  
  /**
   * To search a value inside graph, can to be in position  start or end
   * @param type $array
   * @param type $start_end can to be start or end
   * @param type $search, item for searching
   * @param type $dest, if is 0 return boolean, else return value of position end of node 
   * @return boolean
   */
  public function is_present($array, $start_end, $search, $dest = 0) {

    for ($i = 0; $i < count($array); $i++) {

      if (trim($array[$i][$start_end]) == $search) {
        if ($dest == 0) {
          return true;
        } else {
          return trim($array[$i]['end']);
        }
      }
    }
    return false;
  }

  /**
   * Recursive method for searching the way for finding a cycle
   * @param type $array
   * @param type $position
   * @param type $end
   * @param type $search
   * @return boolean
   */
  public function verify_way($array, $position, $end, $search) {

    for ($i = 0; $i < count($array); $i++) {

      if ($i != $position) {
        if (trim($array[$i]['start']) == $end) {

          echo " Way found " . $end . '<br>';
          if (trim($array[$i]['end']) == $search) {
            echo 'Cycle found for the item ' . $search;
            return true;
          } else {
            return $this->verify_way($array, $i, trim($array[$i]['end']), $search);
          }
        }
      } else {
        continue;
      }
    }
    return false;
  }

}





