<?php

namespace Bibop\PropertyAccessor;

class PropertyAccessor
{
    /** @var ObjectMetadata[] */
    private $objectMetadata = [];

    public function getProperty($object, string $propertyName)
    {
        $metadata = $this->getMetadata($object);
        $prop = $metadata->get($propertyName);

        if ($prop->isPublic()) {
            return $object->{$propertyName};
        }

        if ($prop->hasGetterMethod()) {
            return call_user_func([$object, $prop->getterMethod()]);
        }

        return $prop->privateReader()($object);
    }

    public function setProperty($object, string $propertyName, $value)
    {
        $metadata = $this->getMetadata($object);
        $prop = $metadata->get($propertyName);

        if ($prop->isPublic()) {
            $object->{$propertyName} = $value;
            return;
        }

        if ($prop->hasSetterMethod()) {
            call_user_func_array([$object, $prop->setterMethod()], [$value]);
            return;
        }

        $objProp = &$prop->privateWriter()($object);
        $objProp = $value;
    }

    public function hasProperty($object, string $propertyName): bool
    {
        $metadata = $this->getMetadata($object);
        return $metadata->has($propertyName);
    }

    public function getPropertyNames($object): array
    {
        $metadata = $this->getMetadata($object);
        return array_keys($metadata->getProperties());
    }

    private function getMetadata($object): ObjectMetadata
    {
        $class = get_class($object);
        if (isset($this->objectMetadata[$class])) {
            return $this->objectMetadata[$class];
        }

        $this->objectMetadata[$class] = $metadata = new ObjectMetadata($object);
        return $metadata;
    }
}