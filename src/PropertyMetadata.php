<?php

namespace Bibop\PropertyAccessor;

class PropertyMetadata
{
    private $name;
    private $isPublic = false;
    private $privateWriter;
    private $privateReader;
    private $setterMethod;
    private $getterMethod;

    public function __construct(string $object, string $name, bool $isPublic, ?string $getterMethod, ?string $setterMethod)
    {
        $this->name = $name;
        $this->isPublic = $isPublic;
        $this->getterMethod = $getterMethod;
        $this->setterMethod = $setterMethod;

        if (!$isPublic) {
            $this->privateReader = \Closure::bind(
                function ($object) use ($name) {
                    if (property_exists($object, $name)) {
                        return $object->{$name};
                    }

                    $objectArray = (array) $object;
                    foreach ($objectArray as $key => $value) {
                        if (substr($key, - \strlen($name) - 1) === "\x00" . $name) {
                            return $value;
                        }
                    }

                    return null;
                },
                null,
                $object
            );
            $this->privateWriter = \Closure::bind(
                function & ($object) use ($name) {
                    return $object->{$name};
                },
                null,
                $object
            );
        }
    }

    public function isPublic()
    {
        return $this->isPublic;
    }

    public function privateWriter()
    {
        return $this->privateWriter;
    }
    public function privateReader()
    {
        return $this->privateReader;
    }

    public function hasGetterMethod(): bool
    {
        return $this->getterMethod !== null;
    }

    public function hasSetterMethod(): bool
    {
        return $this->setterMethod !== null;
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