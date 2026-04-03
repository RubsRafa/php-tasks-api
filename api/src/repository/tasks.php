<?php

class TasksRepository {

  private string $file = __DIR__ . '/database/tasks.json';

  public function findAll($completed = null, $page = null, $limit = null) {
    if (!file_exists($this->file)) {
      file_put_contents($this->file, json_encode([]));
    }

    $tasks = json_decode(file_get_contents($this->file), true);
    $total = count($tasks);

    if ($completed !== null) {
        $tasks = array_filter($tasks, function ($task) use ($completed) {
            return $task['completed'] === $completed;
        });
    }

    $tasks = array_values($tasks);

    
    if ($page !== null && $limit !== null) {
      $offset = ($page - 1) * $limit;
      $tasks = array_slice($tasks, $offset, $limit);
    } else if ($limit !== null) {
        $tasks = array_slice($tasks, 0, $limit);
    }
    return [
      'data' => $tasks,
      'pagination' => [
        'page' => $page,
        'limit' => $limit,
        'total' => count($tasks),
        'pages' => isset($limit) ? ceil(count($tasks) / $limit) : null,
      ]
    ];
  }

  public function findById($id) {
    if (!file_exists($this->file)) {
        return null;
    }

    $tasks = json_decode(file_get_contents($this->file), true);

    foreach ($tasks as $task) {
        if ($task['id'] == $id) {
            return $task;
        }
    }

    return null;
}

  public function write($tasks) {
    file_put_contents($this->file, json_encode($tasks));
  }
}