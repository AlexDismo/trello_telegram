<?php

namespace App\Services;

class FormatterService
{
    public function formatTasks(array $tasks): string
    {
        $message = "";

        foreach ($tasks as $task) {
            $message .= "Task ID: {$task['id']}\n";
            $message .= "Name: {$task['name']}\n";
            $message .= "Description: {$task['desc']}\n";
            $message .= "List: {$task['list']}\n";
            if (isset($task['members'])) {
                $message .= "Members: {$task['members']}\n";
            }
            $message .= "-----------------\n";
        }

        return $message;
    }
}
