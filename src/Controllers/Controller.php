<?php

namespace AntoineD\Api\Controllers;

use Exception;

class GenericController
{
    private string $modelClass;
    public function __construct(string $modelClass)
    {
        // echo $modelClass;
        $modelClass = ucfirst($modelClass);

        if (substr($modelClass, -1) === 's') {
            $modelClass = substr($modelClass, 0, -1);
        }

        $modelClass = "AntoineD\\Api\\Models\\$modelClass";
        if (!class_exists($modelClass)) {
            throw new Exception("Model class $modelClass does not exist");
        }

        $this->modelClass = $modelClass;

    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uriSegments = explode('/', $uri);

        $id = $uriSegments[1] ?? null;

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->get($id);
                } else {
                    $this->getAll();
                }
                break;
            case 'POST':
                $this->post();
                break;
            case 'PUT':
                if ($id) {
                    $this->put($id);
                } else {
                    $this->sendResponse(400, "Bad Request: Missing ID for PUT request");
                }
                break;
            case 'DELETE':
                if ($id) {
                    $this->delete($id);
                } else {
                    $this->sendResponse(400, "Bad Request: Missing ID for DELETE request");
                }
                break;
            default:
                $this->sendResponse(405, "Method Not Allowed");
                break;
        }
    }

    private function get(int $id)
    {
        $model = new $this->modelClass();
        $result = $model->find($id);
        $this->sendResponse(200, $result);
    }

    private function getAll()
    {
        $model = new $this->modelClass();
        $result = $model->all();
        $this->sendResponse(200, $result);
    }

    private function post()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, "Invalid JSON");
            return;
        }

        $model = new $this->modelClass();
        $id = $model->create($data);
        $this->sendResponse(201, ['id' => $id]);
    }

    private function put(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, "Invalid JSON");
            return;
        }

        $model = new $this->modelClass();
        $rowCount = $model->update($id, $data);
        $this->sendResponse(200, ['updated' => $rowCount]);
    }

    private function delete(int $id)
    {
        $model = new $this->modelClass();
        $rowCount = $model->delete($id);
        $this->sendResponse(200, ['deleted' => $rowCount]);
    }

    private function sendResponse(int $status, $data)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
