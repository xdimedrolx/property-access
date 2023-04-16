<?php

namespace Bibop\PropertyAccessor\Exception;

use Bibop\PropertyAccessor\Exception;

class MethodNotFoundException extends Exception
{
    private string $methodName;

    public function __construct(string $objectClass, string $methodName)
    {
        parent::__construct(
            "Object \"{$objectClass}\" does not contain \"{$methodName}\" method"
        );

        $this->methodName = $methodName;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}