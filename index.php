<?php
require_once 'controllers/AlgorithmController.php';

$controller = new AlgorithmController();

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'getSteps') {
        $controller->getSteps();
    }
} else {
    $controller->index();
}
