<?php

namespace Bibop\PropertyAccessor\Metadata;

class MetadataGenerator
{
    /**
     * @return array{'properties': array<string, PropertyMetadataInterface>, 'methods': array<string, MethodMetadata>}
     */
    public function generate(object $object): array
    {
        $className = get_class($object);
        $objectMethods = get_class_methods($object);
        $propertyMethods = [];

        $properties = [];
        $methods = [];

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
            $intersect = array_intersect($getters, $objectMethods);
            if (count($intersect) > 0) {
                $getterMethod = array_shift($intersect);
                $propertyMethods[] = $getterMethod;
            }

            $setters = [
                'set'.$camelProp,
                'change'.$camelProp,
                'update'.$camelProp,
            ];
            $intersect = array_intersect($setters, $objectMethods);
            if (count($intersect) > 0) {
                $setterMethod = array_shift($intersect);
                $propertyMethods[] = $setterMethod;
            }

            $propertyName = $reflectionProperty->getName();
            $properties[$propertyName] = new PropertyMetadataInterface(
                $className,
                $propertyName,
                $reflectionProperty->isPublic(),
                $getterMethod,
                $setterMethod
            );
        }

        /** @var array<string, array{getter: string, setter: string}> $cMethods */
        $cMethods = [];
        foreach ($objectMethods as $method) {
            if (in_array($method, $propertyMethods, true)) {
                continue;
            }

            $getter = $setter = $name = null;
            if (preg_match('/^(get|is|can|has)(.*)/i', $method, $matches)) {
                $name = lcfirst($matches[2]);
                $getter = $method;
            } else if (preg_match('/^(set|change|update)(.*)/i', $method, $matches)) {
                $name = lcfirst($matches[2]);
                $setter = $method;
            } else {
                $reflectionMethod = new \ReflectionMethod($object, $method);
                if ($reflectionMethod->getNumberOfRequiredParameters() === 0) {
                    $name = $method;
                    $getter = $method;
                }
            }

            if (!$getter && !$setter) {
                continue;
            }

            if (!isset($cMethods[$name])) {
                $cMethods[$name] = ['getter' => null, 'setter' => null];
            }
            if ($getter) {
                $cMethods[$name]['getter'] = $getter;
            }
            if ($setter) {
                $cMethods[$name]['setter'] = $setter;
            }
        }

        foreach ($cMethods as $name => $accesors) {
            $methods[$name] = new MethodMetadata(
                $className,
                $name,
                $accesors['getter'],
                $accesors['setter']
            );
        }

        return compact('properties', 'methods');
    }

    /**
     * @param object $object
     * @return iterable<\ReflectionProperty>
     */
    private function getReflectionProperties(object $object): iterable
    {
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
     * Camelizes a given string.
     */
    private function camelize(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}