<?php

require_once __DIR__ . '/../repository/tasks.php';

class TasksService {
  private $maxLimit;
  private TasksRepository $repository;

  public function __construct($repository = null, $maxLimit = null) {
        $this->repository = new TasksRepository();
        $this->maxLimit = $maxLimit ?? 100;
    }

  public function getTasks($completed = null, $page = null, $limit = null) {
    if (isset($completed)) {
      if ($completed !== 'true' && $completed !== 'false') {
          throw new Exception("Completed must be true or false", 422);
      }
      $completed = $completed === 'true';
    }

    if ($page !== null) {
      if (filter_var($page, FILTER_VALIDATE_INT) === false) {
          throw new Exception("Page must be a number", 422);
      }
      $page = (int) $page;
      if ($page <= 0) {
          throw new Exception("Page must be a positive integer", 422);
      }
    }
    
    if ($limit !== null) {
      if (filter_var($limit, FILTER_VALIDATE_INT) === false) {
          throw new Exception("Limit must be a number", 422);
      }
      $limit = (int) $limit;
      if ($limit <= 0) {
          throw new Exception("Limit must be a positive integer", 422);
      }
      if ($limit > $this->maxLimit) {
          throw new Exception("Limit must be less than or equal to " . $this->maxLimit, 422);
      }
    }

    return $this->repository->findAll($completed, $page, $limit);
  }

  public function getTask($id) {
    $result = $this->repository->findById($id);
    if ($result) {
        return $result;
    }

    throw new Exception('Task not found', 404);
  }

  public function create($task) {
    $this->validateTitle($task['title'] ?? null);
 
    $result = $this->repository->findAll();
    $tasks = $result['data'];

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
    $result = $this->repository->findAll();
    $tasks = $result['data'];
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            if (isset($data['title'])) {
                $this->validateTitle($data['title'] ?? null);
                $task['title'] = $data['title'];
            }
            if (isset($data['completed'])) {
                if (!is_bool($data['completed'])) {
                    throw new Exception('Task completed must be a boolean', 422);
                }
                $task['completed'] = $data['completed'];
            }
            $this->repository->write($tasks);
            return $task;
        }
    }

    throw new Exception('Task not found', 404);
  }

  public function delete($id) {
    $result = $this->repository->findAll();
    $tasks = $result['data'];
    foreach ($tasks as $index => $task) {
        if ($task['id'] == $id) {
            array_splice($tasks, $index, 1);
            $this->repository->write($tasks);
            return;
        }
    }

    throw new Exception('Task not found', 404);
  }

  private function validateTitle($title) {
    if (empty($title)) {
      throw new Exception('Task title is required', 422);
    }

    if (!is_string($title)) {
      throw new Exception('Task title must be a string', 422);
    }
  }
}