<?php

namespace Bibop\PropertyAccessor\Accessor;

use Bibop\PropertyAccessor\Exception\UninitializedPropertyException;
use Bibop\PropertyAccessor\Metadata\ObjectItemMetadataInterface;
use Bibop\PropertyAccessor\Metadata\PropertyMetadataInterface;

class DefaultAccessor implements AccessorInterface
{
    /**
     * @var array<string, callable>
     */
    private $readAccessors = [];

    /**
     * @var array<string, callable>
     */
    private $writeAccessors = [];

    public function getValue(object $object, ObjectItemMetadataInterface $metadata)
    {
        if ($metadata->getterMethod()) {
            return $object->{$metadata->getterMethod()}();
        }

        if (!$metadata instanceof PropertyMetadataInterface) {
            // fixme: throw error?
            return null;
        }

        if ($metadata->isPublic()) {
            return $object->{$metadata->name()};
        }

        $accessor = $this->readAccessors[$metadata->className()] ?? null;
        if (null === $accessor) {
            $accessor = \Closure::bind(static function ($o, $name) {
                return $o->$name;
            }, null, $metadata->className());
            $this->readAccessors[$metadata->className()] = $accessor;
        }

        try {
            return $accessor($object, $metadata->name());
        } catch (\Error $e) {
            // handle uninitialized properties in PHP >= 7.4
            if (preg_match('/^Typed property ([\w\\\\@]+)::\$(\w+) must not be accessed before initialization$/', $e->getMessage(), $matches)) {
                throw new UninitializedPropertyException($metadata->className(), $metadata->name(), $e);
            }

            throw $e;
        }
    }

    public function setValue(object $object, $value, ObjectItemMetadataInterface $metadata): void
    {
        if ($metadata->setterMethod()) {
            $object->{$metadata->setterMethod()}($value);

            return;
        }

        if (!$metadata instanceof PropertyMetadataInterface) {
            // fixme: throw error?
            return;
        }

        if ($metadata->isPublic()) {
            $object->{$metadata->name()} = $value;
            return;
        }

        $accessor = $this->writeAccessors[$metadata->className()] ?? null;
        if (null === $accessor) {
            $accessor = \Closure::bind(static function ($o, $name, $value): void {
                $o->$name = $value;
            }, null, $metadata->className());
            $this->writeAccessors[$metadata->className()] = $accessor;
        }

        $accessor($object, $metadata->name(), $value);
    }
}