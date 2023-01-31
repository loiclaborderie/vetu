<?php
namespace App\data;

class Todo {
    public int $id;
    public string $task;
    public bool $completed;
    public string $description;
    private static int $count = 1;
    public function __construct($task, $description){
        $this->completed = false;
        $this->task = $task;
        $this->description = $description;
        $this->id = self::$count;
        self::$count++;
    }
}
