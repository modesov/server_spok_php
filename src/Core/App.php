<?php

namespace App\Core;

use ReflectionException;
use ReflectionClass;

class App
{
    public function __construct(
        protected Router $router,
        protected Response $response
    )
    {}

    public function run(): void
    {
        $this->response
            ->setResult($this->router->getResult())
            ->send();
    }

    /**
     * @throws ReflectionException
     */
    public static function appInstance(): static
    {
        return new static(...static::getParamsForInstance(static::class));
    }

    /**
     * @throws ReflectionException
     */
    public static function getParamsForInstance($className): array
    {
        $reflectClass = new ReflectionClass($className);
        $params = [];
        $parameters = $reflectClass->getConstructor()?->getParameters() ?: [];
        foreach ($parameters as $parameter) {
            $paramsName = $parameter->getType()?->getName();
            if (class_exists($paramsName)) {
                $params[] = new $paramsName(...static::getParamsForInstance($paramsName));
            }
        }

        return $params;
    }
}