<?php

require_once __DIR__ . '/../service/tasks.php';

class TasksController {

    private TasksService $service;

    public function __construct($service = null) {
        $this->service = new TasksService();
    }

    public function getTasks() {
        return $this->service->getTasks();
    }

    public function getTask($id) {
        return $this->service->getTask($id);
    }

    public function createTask($task) {
        return $this->service->create($task);
    }

    public function updateTask($id, $data) {
        return $this->service->update($id, $data);
    }

    public function deleteTask($id) {
        return $this->service->delete($id);
    }
}