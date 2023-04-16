<?php

namespace Bibop\PropertyAccessor\Metadata;

class MethodMetadata implements ObjectItemMetadataInterface
{
    /** @var class-string */
    private string $className;
    private string $name;
    private ?string $setterMethod;
    private ?string $getterMethod;

    public function __construct(string $className, string $name, ?string $getterMethod, ?string $setterMethod)
    {
        $this->className = $className;
        $this->name = $name;
        $this->getterMethod = $getterMethod;
        $this->setterMethod = $setterMethod;
    }

    public function className(): string
    {
        return $this->className;
    }
    public function name(): string
    {
        return $this->name;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getterMethod(): ?string
    {
        return $this->getterMethod;
    }

    public function setterMethod(): ?string
    {
        return $this->setterMethod;
    }
}