<?php

namespace Bibop\PropertyAccessor;

use Bibop\PropertyAccessor\Accessor\AccessorInterface;
use Bibop\PropertyAccessor\Accessor\DefaultAccessor;
use Bibop\PropertyAccessor\Exception\PropertyDoesNotExistException;
use Bibop\PropertyAccessor\Exception\PropertyNotFoundException;
use Bibop\PropertyAccessor\Exception\UninitializedPropertyException;
use Bibop\PropertyAccessor\Metadata\MetadataGenerator;
use Bibop\PropertyAccessor\Metadata\ObjectMetadata;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Psr\Cache\CacheItemPoolInterface;

class PropertyAccessor
{
    private CacheItemPoolInterface $cache;
    private MetadataGenerator $metadataGenerator;
    private AccessorInterface $accessor;
    private bool $throwErrorIfPropertyDoesNotExist;

    public function __construct(
        ?CacheItemPoolInterface $cache = null,
        bool $throwErrorIfPropertyDoesNotExist = false
    ) {
        $this->cache = $cache ?? new ArrayCachePool();
        $this->metadataGenerator = new MetadataGenerator();
        $this->accessor = new DefaultAccessor();
        $this->throwErrorIfPropertyDoesNotExist = $throwErrorIfPropertyDoesNotExist;
    }

    public static function build(): self
    {
        return new self();
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @return mixed|null
     * @throws PropertyNotFoundException
     * @throws UninitializedPropertyException
     * @throws PropertyDoesNotExistException
     */
    public function getProperty(object $object, string $propertyName)
    {
        $metadata = $this->getMetadata($object);

        if ($metadata->hasProperty($propertyName)) {
            return $this->accessor->getValue($object, $metadata->getProperty($propertyName));
        }

        if ($metadata->hasMethod($propertyName)) {
            return $this->accessor->getValue($object, $metadata->getMethod($propertyName));
        }

        if ($this->throwErrorIfPropertyDoesNotExist) {
            throw new PropertyDoesNotExistException(get_class($object), $propertyName);
        }

        return null;
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed|null $value
     * @return $this
     * @throws PropertyDoesNotExistException
     */
    public function setProperty(object $object, string $propertyName, $value): self
    {
        $metadata = $this->getMetadata($object);

        if ($metadata->hasProperty($propertyName)) {
            $this->accessor->setValue($object, $value, $metadata->getProperty($propertyName));
            return $this;
        }

        if ($metadata->hasMethod($propertyName)) {
            $this->accessor->setValue($object, $value, $metadata->getMethod($propertyName));
            return $this;
        }

        if ($this->throwErrorIfPropertyDoesNotExist) {
            throw new PropertyDoesNotExistException(get_class($object), $propertyName);
        }

        return $this;
    }

    public function hasProperty(object $object, string $propertyName): bool
    {
        $metadata = $this->getMetadata($object);

        if ($metadata->hasProperty($propertyName)) {
            return true;
        }

        if ($metadata->hasMethod($propertyName)) {
            return true;
        }

        return false;
    }

    public function getPropertyNames($object): array
    {
        $metadata = $this->getMetadata($object);
        return array_keys($metadata->getProperties());
    }

    private function getMetadata(object $object): ObjectMetadata
    {
        $class = get_class($object);

        $key = preg_replace('/\\\/', '.', $class);
        $metadata = $this->cache->get($key);
        if ($metadata) {
            return $metadata;
        }

        $data = $this->metadataGenerator->generate($object);

        $metadata = new ObjectMetadata($class, $data['properties'], $data['methods']);

        $this->cache->set($key, $metadata);

        return $metadata;
    }
}