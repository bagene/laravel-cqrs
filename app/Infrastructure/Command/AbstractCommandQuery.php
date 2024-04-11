<?php

namespace App\Infrastructure\Command;

use App\Contracts\Bus\CommandQueryContract;

abstract class AbstractCommandQuery implements CommandQueryContract
{
    /**
     * @return array<string, mixed> $data
     */
    public function toArray(): array
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $response = [];

        foreach ($reflectionClass->getMethods(\ReflectionProperty::IS_PUBLIC) as $method) {
            $methodName = $method->getName();

            if ($method->isConstructor() || $method->isAbstract() || $method->isStatic() || 'toArray' === $methodName) {
                continue;
            }

            $key = lcfirst(ltrim($methodName, 'get'));
            $response[$key] = $this->$methodName();
        }

        return $response;
    }
}
