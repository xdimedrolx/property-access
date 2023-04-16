<?php

namespace Bibop\PropertyAccessor\Metadata;

use Bibop\PropertyAccessor\Exception\MethodNotFoundException;
use Bibop\PropertyAccessor\Exception\PropertyNotFoundException;

/**
 * @template T
 */
class ObjectMetadata
{
    /**
     * @var array<string, PropertyMetadataInterface>
     */
    private array $properties = [];

    /**
     * @var array<string, MethodMetadata>
     */
    private array $methods = [];

    /**
     * @var class-string<T>
     */
    private string $objectClass;

    /**
     * @param class-string<T> $objectClass
     * @param array<string, PropertyMetadataInterface> $properties
     * @param array<string, MethodMetadata> $methods
     */
    public function __construct(string $objectClass, array $properties, array $methods)
    {
        $this->properties = $properties;
        $this->methods = $methods;
        $this->objectClass = $objectClass;
    }

    public function getProperty(string $name): PropertyMetadataInterface
    {
        if (!isset($this->properties[$name])) {
            throw new PropertyNotFoundException($this->objectClass, $name);
        }

        return $this->properties[$name];
    }

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * @return  array<string, PropertyMetadataInterface>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getMethod(string $name): MethodMetadata
    {
        if (!isset($this->methods[$name])) {
            throw new MethodNotFoundException($this->objectClass, $name);
        }

        return $this->methods[$name];
    }

    public function hasMethod(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    /**
     * @return array<string, MethodMetadata>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}