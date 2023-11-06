<?php

namespace App\Core;

class Config
{
    private string $path = __DIR__ . '/../../config/';
    private array $configs;

    private static ?Config $instance = null;

    private function __clone() {}

    private function __construct(){
        $this->setConfigs();
    }

    private function setConfigs(): void
    {
        $files = scandir($this->path);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $name = str_replace('.php', '', $file);

            $this->configs[$name] = (object)include $this->path . $file;
        }
    }

    public static function getInstance(): ?static
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(): object
    {
        return (object)$this->configs;
    }

    public function getArray(): array
    {
        return $this->configs;
    }
}