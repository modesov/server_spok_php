<?php

namespace App\Controllers\Api\V1;

class NotFound404
{
    public function __invoke(): array
    {
        return [
            'code' => 404,
            'message' => '404 not found',
            'description' => 'Такая страница не найдена будьте осторожны!'
        ];
    }
}