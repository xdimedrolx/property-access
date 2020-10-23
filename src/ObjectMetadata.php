<?php

namespace Bibop\PropertyAccessor;

class ObjectMetadata
{
    private $properties = [];

    public function __construct($object)
    {
        $this->properties = $this->properties($object);
    }

    public function get(string $name): PropertyMetadata
    {
        return $this->properties[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * @return PropertyMetadata[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param $object
     * @return iterable|\ReflectionProperty[]
     */
    private function getReflectionProperties($object): iterable
    {
        if ($object === null) {
            return [];
        }

        $reflectionClass = new \ReflectionObject($object);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            yield $property;
        }


        // Parent properties are not included in the reflection class, so we'll
        // go up the inheritance chain and collect private properties.
        while ($reflectionClass = $reflectionClass->getParentClass()) {
            foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
                yield $property;
            }
        }
    }

    /**
     * @inheritdoc
     */
    private function properties($object): array
    {
        $props = [];
        $objectClass = get_class($object);
        $methods = get_class_methods($object);

        foreach ($this->getReflectionProperties($object) as $reflectionProperty) {
            $camelProp = $this->camelize($reflectionProperty->getName());
            $getterMethod = $setterMethod = null;

            $getters = [
                'get'.$camelProp,
                lcfirst($camelProp),
                'is'.$camelProp,
                'has'.$camelProp,
                'can'.$camelProp
            ];
            $intersect = array_intersect($getters, $methods);
            if (count($intersect) > 0) {
                $getterMethod = array_pop($intersect);
            }

            $setters = [
                'set'.$camelProp,
                'change'.$camelProp,
                'update'.$camelProp,
            ];
            $intersect = array_intersect($setters, $methods);
            if (count($intersect) > 0) {
                $setterMethod = array_pop($intersect);
            }

            $props[$reflectionProperty->getName()] = new PropertyMetadata(
                $objectClass,
                $reflectionProperty->getName(),
                $reflectionProperty->isPublic(),
                $getterMethod,
                $setterMethod
            );
        }

        return $props;
    }

    /**
     * Camelizes a given string.
     */
    private function camelize(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}