<?php
session_start();
require_once '../helpers/db.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = "../controllers/{$controllerName}.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile; 
    $controllerInstance = new $controllerName();
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        die('Action not found');
    }
} else {
    die('Controller not found');
}
?>
