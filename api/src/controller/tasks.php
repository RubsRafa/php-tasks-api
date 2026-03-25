<?php

require_once __DIR__ . '/../service/tasks.php';

class TasksController {

    private TasksService $service;

    public function __construct($service = null) {
        $this->service = new TasksService();
    }

    public function getTasks() {
        try {
            return $this->service->getTasks();
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getCode(), "message" => $e->getMessage()]);
        }
    }

    public function getTask($id) {
        try {
            return $this->service->getTask($id);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getCode(), "message" => $e->getMessage()]);
        }
    }

    public function createTask($task) {
        try {
            return $this->service->create($task);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getCode(), "message" => $e->getMessage()]);
        }
    }

    public function updateTask($id, $data) {
        try {
            return $this->service->update($id, $data);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getCode(), "message" => $e->getMessage()]);
        }
    }

    public function deleteTask($id) {
        try {
            return $this->service->delete($id);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getCode(), "message" => $e->getMessage()]);
        }
    }
}