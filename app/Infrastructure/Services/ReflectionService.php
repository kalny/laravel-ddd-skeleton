<?php

namespace App\Infrastructure\Services;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ReflectionService
{
    /** @var array<string, ReflectionClass> */
    private array $classCache = [];

    /** @var array<string, array<string, ReflectionProperty>> */
    private array $propertyCache = [];

    public function getValue(object $object, string $propertyName): mixed
    {
        $property = $this->getReflectionProperty($object::class, $propertyName);

        return $property->getValue($object);
    }

    public function setValue(object $object, string $propertyName, mixed $propertyValue): void
    {
        $property = $this->getReflectionProperty($object::class, $propertyName);

        $property->setValue($object, $propertyValue);
    }

    public function createObject(string $className, array $fields): object
    {
        $reflection = $this->getReflectionClass($className);

        try {
            $object = $reflection->newInstanceWithoutConstructor();
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }

        foreach ($fields as $propertyName => $value) {
            $property = $this->getReflectionProperty($className, $propertyName);
            $property->setValue($object, $value);
        }

        return $object;
    }

    private function getReflectionClass(string $className): ReflectionClass
    {
        if (!isset($this->classCache[$className])) {
            try {
                $this->classCache[$className] = new ReflectionClass($className);
            } catch (ReflectionException $exception) {
                throw new InvalidArgumentException($exception->getMessage());
            }
        }

        return $this->classCache[$className];
    }

    private function getReflectionProperty(string $className, string $propertyName): ReflectionProperty
    {
        if (!isset($this->propertyCache[$className][$propertyName])) {
            $reflection = $this->getReflectionClass($className);

            if (!$reflection->hasProperty($propertyName)) {
                throw new InvalidArgumentException(
                    "Property '{$propertyName}' does not exist in class '{$className}'."
                );
            }

            $property = $reflection->getProperty($propertyName);

            $this->propertyCache[$className][$propertyName] = $property;
        }

        return $this->propertyCache[$className][$propertyName];
    }
}
