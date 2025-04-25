<?php
require_once 'models/AlgorithmModel.php';

class AlgorithmController
{
  private $model;

  public function __construct()
  {
    $this->model = new AlgorithmModel();
  }

  public function index()
  {
    require_once 'views/template.php';
  }

  //getting the algorithm and array from url to process data w getsteps method
  public function getSteps()
  {
    header('Content-Type: application/json');
    $algorithm = isset($_GET['algorithm']) ? $_GET['algorithm'] : '';
    $array = isset($_GET['array']) ? json_decode($_GET['array'], true) : [];
    $steps = $this->model->getSteps($algorithm, $array);
    echo json_encode($steps);
  }
}
