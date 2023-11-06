<?php

namespace App\Controllers\Api\V1\Tasks;

use App\Core\Route;

class TasksController extends TasksGroupController
{
    #[Route(path: '/', name: 'tasks', methods: ['GET'])]
    public function __invoke(): array
    {
        return [
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
    }
}