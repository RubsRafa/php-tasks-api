<?php

class TasksRepository {

  private string $file = __DIR__ . '/database/tasks.json';

  public function read() {
    if (!file_exists($this->file)) {
      file_put_contents($this->file, json_encode([]));
    }

    return json_decode(file_get_contents($this->file), true);
  }

  public function write($tasks) {
    file_put_contents($this->file, json_encode($tasks));
  }
}