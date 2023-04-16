<?php

namespace Bibop\PropertyAccessor\Exception;

use Bibop\PropertyAccessor\Exception;

class UninitializedPropertyException extends Exception
{
    private string $propertyName;

    public function __construct(string $objectClass, string $propertyName, \Throwable $previous)
    {
        parent::__construct(
            "Uninitialized property \"{$objectClass}::{$propertyName}\"",
            0,
            $previous
        );

        $this->propertyName = $propertyName;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}