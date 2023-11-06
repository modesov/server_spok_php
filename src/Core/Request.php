<?php

namespace App\Core;

class Request
{
    private readonly array $get;
    private readonly array $post;
    private readonly array $server;
    private readonly array $files;
    private array $cookies;

    public function __construct(){
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    public function get(): array
    {
        return $this->get;
    }

    public function post(): array
    {
        return $this->post;
    }

    public function server(): array
    {
        return $this->server;
    }

    public function files(): array
    {
        return $this->files;
    }

    public function cookies(): array
    {
        return $this->cookies;
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getCookie(string $key): ?string
    {
        return $this->cookies[$key] ?? null;
    }

    public function setCookie(
        string $name,
        string $value = "",
        int $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false
    ): bool
    {
        return setcookie($name, $value = "", $expires_or_options = 0, $path = "", $domain = "", $secure = false, $httponly = false);
    }
}