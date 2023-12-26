<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/model.php';


function handleRoute($method, $route) {
    $route = trim($route, '/');
    $route = $route ? $route : 'index';

    $action_name = "action_{$method}_{$route}";

    if (function_exists($action_name)) {
        $action_name();
        return;
    }
    
    action_get_404();
}

function view($name, $data = []) {
    $name = preg_replace('/\W/', '', $name);
    $view_name = realpath(__DIR__ . "/views/{$name}.html");
    if (!file_exists($view_name)) {
        action_get_404();
        return;
    }
    extract($data);
    require $view_name;
}


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleRoute($method, $uri);
