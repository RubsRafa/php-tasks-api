<?php

require_once __DIR__ . '/../repository/tasks.php';

class TasksService {
  private TasksRepository $repository;

  public function __construct($repository = null) {
        $this->repository = new TasksRepository();
    }

  public function getTasks() {
    return $this->repository->read();
  }

  public function getTask($id) {
    $tasks = $this->repository->read();
    foreach ($tasks as $task) {
        if ($task['id'] == $id) {
            return $task;
        }
    }

    http_response_code(404);
    throw new Exception('Task not found', 404);
  }

  public function create($task) {
    $this->validateTitle($task['title']);
 
    $tasks = $this->repository->read();

    $newTask = [
      'id' => uniqid(),
      'title' => $task['title'],
      'completed' => false,
      'created_at' => date('Y-m-d H:i:s'),
    ];

    $tasks[] = $newTask;

    $this->repository->write($tasks);

    return $newTask;
  }

  public function update($id, $data) {
    $tasks = $this->repository->read();
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            if (isset($data['title'])) {
                $this->validateTitle($data['title']);
                $task['title'] = $data['title'];
            }
            if (isset($data['completed'])) {
                if (!is_bool($data['completed'])) {
                    http_response_code(422);
                    throw new Exception('Task completed must be a boolean', 422);
                }
                $task['completed'] = $data['completed'];
            }
            $this->repository->write($tasks);
            return $task;
        }
    }

    http_response_code(404);
    throw new Exception('Task not found', 404);
  }

  public function delete($id) {
    $tasks = $this->repository->read();
    foreach ($tasks as $index => $task) {
        if ($task['id'] == $id) {
            array_splice($tasks, $index, 1);
            $this->repository->write($tasks);
        }
    }

    http_response_code(404);
    throw new Exception('Task not found', 404);
  }

  private function validateTitle($title) {
    if (empty($title)) {
      http_response_code(422);
      throw new Exception('Task title is required', 422);
    }

    if (!is_string($title)) {
      http_response_code(422);
      throw new Exception('Task title must be a string', 422);
    }
  }
}