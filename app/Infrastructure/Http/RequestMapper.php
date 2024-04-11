<?php

namespace App\Infrastructure\Http;

use App\Contracts\Bus\CommandQueryContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class RequestMapper
{
    /**
     * @param array<string|int, mixed> $data
     * @param class-string $type
     * @return CommandQueryContract
     * @throws \ReflectionException
     */
    protected function createRequestData(array $data, string $type): CommandQueryContract
    {
        $reflectionClass = new ReflectionClass($type);

        $formattedData = [];

        /** @var ReflectionMethod $constructor */
        $constructor = $reflectionClass->getConstructor();

        foreach ($constructor->getParameters() as $parameter) {
            $propertyName = $parameter->getName();
            $defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
            $value = $data[$propertyName] ?? $defaultValue;

            $formattedData[$propertyName] = $this->formatValueType($parameter, $value);
        }

        /** @var CommandQueryContract $type */
        return new $type(...$formattedData);
    }

    protected function formatValueType(ReflectionParameter|string $parameter, mixed $value): mixed
    {
        /** @var ReflectionParameter $parameter */
        $parameterType = $parameter->getType();
        /** @var ReflectionParameter $parameterType */
        $isNullable = $parameterType->allowsNull();
        /** @var ReflectionParameter $parameterType */
        $type = $parameterType->getName();

        $valueType = gettype($value);
        if ('object' === $valueType) {
            /** @var object $value */
            $valueType = get_class($value);

            return $this->createRequestData((array) $value, $valueType);
        }

        if (($isNullable && null === $value) || $type === $valueType) {
            return $value;
        }

        return match ($type) {
            'array' => array_map(fn ($item) => $this->formatValueType(gettype($item), $item), is_array($value) ? $value : []),
            'int' => is_numeric($value) ? (int) $value : 0,
            'float' => is_numeric($value) ? (float) $value : 0.0,
            default => $value,
        };
    }

    /**
     * @param Request $request
     * @param class-string $type
     * @return CommandQueryContract
     */
    public function fromBody(Request $request, string $type): mixed
    {
        $data = $request->all();

        return $this->createRequestData($data, $type);
    }

    /**
     * @param Request $request
     * @param class-string $type
     * @return CommandQueryContract
     */
    public function fromQuery(Request $request, string $type): mixed
    {
        /** @var array<string, mixed> $data */
        $data = $request->query();

        return $this->createRequestData($data, $type);
    }

    /**
     * @param Request $request
     * @param class-string $type
     * @return CommandQueryContract
     */
    public function fromRoute(Request $request, string $type): mixed
    {
        /** @var Route $route */
        $route = $request->route();
        /** @var array<string, mixed> $data */
        $data = $route->parameters;

        return $this->createRequestData($data, $type);
    }

    /**
     * @param Request $request
     * @param class-string $type
     * @return CommandQueryContract
     */
    public function fromHeaderAndBody(Request $request, string $type): mixed
    {
        $data = $request->all();
        /** @var array<string, array{0: string}> $header */
        $header = $request->header();

        $data = array_merge($data, array_map(fn ($value) => $value[0], $header));

        return $this->createRequestData($data, $type);
    }
}
