<?php
namespace PS\GestionBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\TaskListEvent;
use PS\GestionBundle\Model\TaskModel;

class MyTaskListListener {

    // ...

    public function onListTasks(TaskListEvent $event) {

        foreach($this->getTasks() as $task) {
            $event->addTask($task);
        }

    }

    protected function getTasks() {
        return array(
            new TaskModel('make stuff', 30, TaskModel::COLOR_GREEN),
            new TaskModel('make more stuff', 60),
            new TaskModel('some more tasks to do', 10, TaskModel::COLOR_RED)
        );
    }

}