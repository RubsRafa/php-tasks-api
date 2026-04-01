<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/controller/tasks.php';

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/index.php', '', $uri);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '') {
    $uri = '/';
}

$controller = new TasksController();

$segments = explode('/', trim($uri, '/'));

if ($segments[0] === 'tasks') {

    if ($method === 'GET' && count($segments) === 1) {
        try {
            http_response_code(200);
            echo json_encode($controller->getTasks());
        } catch (\Throwable $th) {
            http_response_code($th->getCode() ?: 500);
            echo json_encode(["error" => $th->getMessage()]);
        }
        exit;
    }

    if ($method === 'GET' && count($segments) === 2) {
        $id = $segments[1];
        try {
            $result = $controller->getTask($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Throwable $th) {
            http_response_code($th->getCode() ?: 500);
            echo json_encode(["error" => $th->getMessage()]);
        }
        exit;
    }

    if ($method === 'POST' && count($segments) === 1) {
        try {
            $body = json_decode(file_get_contents("php://input"), true);
            
            $result = $controller->createTask($body);
            
            http_response_code(201);
            echo json_encode($result);
        } catch (\Throwable $th) {
            http_response_code($th->getCode() ?: 500);
            echo json_encode(["error" => $th->getMessage()]);
        }
        exit;
    }

    if ($method === 'PATCH' && count($segments) === 2) {
        try {
            $body = json_decode(file_get_contents("php://input"), true);
            $id = $segments[1];
            http_response_code(200);
            echo json_encode($controller->updateTask($id, $body));
        } catch (\Throwable $th) {
            http_response_code($th->getCode() ?: 500);
            echo json_encode(["error" => $th->getMessage()]);
        }
        exit;
    }

    if ($method === 'DELETE' && count($segments) === 2) {
        try {
            $id = $segments[1];
            http_response_code(204);
            echo json_encode($controller->deleteTask($id));
        } catch (\Throwable $th) {
            http_response_code($th->getCode() ?: 500);
            echo json_encode(["error" => $th->getMessage()]);
        }
        exit;
    }
}


http_response_code(404);
echo json_encode(["error" => "Not found", "uri" => $uri]);