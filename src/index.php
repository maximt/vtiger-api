<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/model.php';


function handleRoute($method, $route) {
    $route = trim($route, '/');
    $route = $route ? $route : 'index';

    $action_name = strtolower("action_{$method}_{$route}");

    if (function_exists($action_name)) {
        $action_name();
        return;
    }
    
    action_error(404);
}

function view($name, $data = []) {
    $path_templates = __DIR__ . "/views/";
    
    $name = preg_replace('/\W/', '', $name);
    $view_name = "{$name}.html";
    
    $loader = new \Twig\Loader\FilesystemLoader($path_templates);

    if (!$loader->exists($view_name)) {
        action_error(501);
        return;
    }

    $twig = new \Twig\Environment($loader);

    ob_clean();
    echo $twig->render($view_name, $data);
    ob_flush();
}

function redirect(string $url) {
    ob_clean();
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /{$url}");
    ob_flush();
    die(); 
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleRoute($method, $uri);
