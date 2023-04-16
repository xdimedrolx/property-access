<?php

namespace Bibop\PropertyAccessor\Exception;

use Bibop\PropertyAccessor\Exception;

class PropertyNotFoundException extends Exception
{
    private string $propertyName;

    public function __construct(string $objectClass, string $propertyName)
    {
        parent::__construct(
            "Object \"{$objectClass}\" does not contain \"{$propertyName}\" property"
        );

        $this->propertyName = $propertyName;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}