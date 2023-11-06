<?php

namespace App\Core;

#[\Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $name = '',
        public array $methods = ['GET'],
        public string $class = '',
        public string $action = '',
        public array $params = []
    ){}

    public function run(): string | array
    {
        $class = new $this->class;
        if ($this->action === '__invoke') {
            return $class(...$this->params);
        } else {
            $method = $this->action;
            return $class->$method(...$this->params);
        }
    }
}