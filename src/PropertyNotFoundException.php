<?php

namespace Bibop\PropertyAccessor;

class PropertyNotFoundException extends \Exception
{
    private $propertyName;

    public function __construct(string $objectClass, string $propertyName)
    {
        parent::__construct("Object \"{$objectClass}\" does not contain \"{$propertyName}\" property");

        $this->propertyName = $propertyName;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}