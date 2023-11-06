<?php

namespace App\Core;

use App\Controllers\Api\V1\NotFound404;
use ReflectionClass;
use ReflectionException;

class Router
{
    protected array $routes = [];
    protected object $config;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        protected Request $request,
    )
    {
        $this->config = Config::getInstance()->get()->app;

        $this->getRoutes($this->config->rootPath . $this->config->pathControllers);
    }

    public function getResult(): string | array
    {
        $route = $this->getRoute();

        return $route ? $route->run() : (new NotFound404())();
    }

    /**
     * @return Route|null
     */
    private function getRoute(): ?Route
    {
        // /api/tasks/56?test=test&rem=tre
        $uri = trim(explode('?', $this->request->getUri())[0], '/');
        $arUri = explode('/', $uri);
        /**
         * @var $route Route
         */
        foreach ($this->routes as $route) {
            if (!in_array($this->request->getMethod(), $route->methods)) {
                continue;
            }

            $path = trim($route->path, '/');
            if ($path === $uri) {
                return $route;
            }

            $arPath = explode('/', $path);
            if (count($arUri) !== count($arPath)) {
                continue;
            }

            $is = true;
            $arParams = [];
            foreach ($arUri as $key => $value) {
                preg_match('/<.+>/', $arPath[$key], $matches);

                if (!empty($matches)) {
                    [$name, $type] = explode(':', trim($matches[0], '<,>'));
                    if ($type === 'int' && is_numeric($value)) {
                        $arParams[$name] = (int)$value;
                    } elseif ($type === 'string' && !is_numeric($value)) {
                        $arParams[$name] = $value;
                    } else {
                        $is = false;
                        break;
                    }
                } elseif ($value !== $arPath[$key]) {
                    $is = false;
                    break;
                }
            }

            if ($is) {
                if (!empty($arParams)) {
                    $route->params = $arParams;
                }
                return $route;
            }
        }

        return null;
    }

    /**
     * @param string $path
     * @throws ReflectionException
     */
    private function getRoutes(string $path): void
    {
        $files = scandir($path);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $pathToFile = $path . DIRECTORY_SEPARATOR . $file;

            if (!is_dir($pathToFile)) {
                $className = 'App\\' . explode('/src/', $pathToFile)[1];
                $className = str_replace('/', '\\', $className);
                $className = str_replace('.php', '', $className);
                $reflect = new ReflectionClass($className);

                $attributesClass = $reflect->getAttributes();

                if (!$attributesClass) {
                    $parent = $reflect->getParentClass();
                    if (gettype($parent) === 'object') {
                        $attributesClass = $parent->getAttributes();
                    }
                }

                $prefix = '';
                foreach ($attributesClass as $attribute) {
                    if ($attribute->getName() === Route::class) {
                        $prefix = $attribute->getArguments()['path'];
                        break;
                    }
                }

                foreach ($reflect->getMethods() as $method) {
                    /**
                     * @var $route Route
                     */
                    foreach ($method->getAttributes() as $attribute) {
                        if ($attribute->getName() === Route::class) {
                            $route = $attribute->newInstance();
                            $route->path = $prefix.$route->path;
                            $route->class = $method->class;
                            $route->action = $method->getName();
                            foreach($method->getParameters() as $parameter) {
                                $route->params[] = [
                                    'name' => $parameter->getName(),
                                    'type' => $parameter->getType(),
                                ];
                            }
                            $this->routes[] = $route;
                            break;
                        }
                    }
                }
            } else {
                $this->getRoutes($pathToFile);
            }
        }
    }
}