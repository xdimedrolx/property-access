<?php

namespace Bibop\PropertyAccessor\Metadata;

class PropertyMetadataInterface implements ObjectItemMetadataInterface
{
    /** @var class-string */
    private string $className;
    private string $name;
    private bool $isPublic;
    private ?string $setterMethod;
    private ?string $getterMethod;

    public function __construct(
        string $className,
        string $name,
        bool $isPublic,
        ?string $getterMethod,
        ?string $setterMethod
    ) {
        $this->className = $className;
        $this->name = $name;
        $this->isPublic = $isPublic;
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
        return $this->isPublic;
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