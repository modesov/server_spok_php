<?php

namespace App\Controllers\Api\V1\Tasks;

use App\Controllers\Api\V1\NotFound404;
use App\Core\Route;

class TaskController extends TasksGroupController
{
    #[Route(path: '/<id:int>', name: 'task', methods: ['GET'])]
    public function __invoke(int $id): array
    {
        $tasks = [
            [
                'id' => 1,
                'title' => 'Убраться на столе',
                'done' => true,
                'date' => '05.11.2023',
            ],
            [
                'id' => 2,
                'title' => 'Написать роутинг',
                'done' => true,
                'date' => '05.11.2023',
            ],
            [
                'id' => 3,
                'title' => 'Создать репозиторий',
                'done' => false,
                'date' => '05.11.2023',
            ]
        ];

        $task = array_filter($tasks, fn($el) => $el['id'] === $id);
        return $task ? current($task) : (new NotFound404())();
    }
}