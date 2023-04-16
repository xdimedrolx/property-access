<?php

namespace Bibop\PropertyAccessor\Accessor;

use Bibop\PropertyAccessor\Metadata\ObjectItemMetadataInterface;

interface AccessorInterface
{
    /**
     * @return mixed
     */
    public function getValue(object $object, ObjectItemMetadataInterface $metadata);

    /**
     * @param mixed $value
     */
    public function setValue(object $object, $value, ObjectItemMetadataInterface $metadata): void;
}