<?php

namespace Bibop\PropertyAccessor\Tests;

use Bibop\PropertyAccessor\ObjectMetadata;
use Bibop\PropertyAccessor\PropertyNotFoundException;
use Bibop\PropertyAccessor\Tests\Models\Employee;
use Bibop\PropertyAccessor\Tests\Models\User;
use PHPUnit\Framework\TestCase;

final class ObjectMetadataTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $user = new User(1);
        $metadata = new ObjectMetadata($user);
        $this->assertInstanceOf(ObjectMetadata::class, $metadata);
    }

    public function testProperties()
    {
        $user = new User(1);
        $metadata = new ObjectMetadata($user);
        $this->assertCount(4, $metadata->getProperties());

        $prop = $metadata->get('id');
        $this->assertFalse($prop->isPublic());
        $this->assertEquals('getId', $prop->getterMethod());
        $this->assertEquals('setId', $prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());

        $prop = $metadata->get('name');
        $this->assertTrue($prop->isPublic());
        $this->assertNull($prop->getterMethod());
        $this->assertNull($prop->setterMethod());
        $this->assertNull($prop->privateReader());
        $this->assertNull($prop->privateWriter());

        $prop = $metadata->get('email');
        $this->assertFalse($prop->isPublic());
        $this->assertEquals('email', $prop->getterMethod());
        $this->assertEquals('setEmail', $prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());

        $prop = $metadata->get('address');
        $this->assertFalse($prop->isPublic());
        $this->assertNull($prop->getterMethod());
        $this->assertNull($prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());
    }

    public function testGetPropertyMetadataWhenItDoesNotExist()
    {
        $this->expectException(PropertyNotFoundException::class);

        $user = new User(1);
        $metadata = new ObjectMetadata($user);
        $metadata->get('recordId');
    }

    public function testInheritedProperties()
    {
        $employee = new Employee(1);
        $metadata = new ObjectMetadata($employee);
        $this->assertCount(4, $metadata->getProperties());

        $prop = $metadata->get('id');
        $this->assertFalse($prop->isPublic());
        $this->assertEquals('getId', $prop->getterMethod());
        $this->assertEquals('setId', $prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());

        $prop = $metadata->get('name');
        $this->assertTrue($prop->isPublic());
        $this->assertNull($prop->getterMethod());
        $this->assertNull($prop->setterMethod());
        $this->assertNull($prop->privateReader());
        $this->assertNull($prop->privateWriter());

        $prop = $metadata->get('email');
        $this->assertFalse($prop->isPublic());
        $this->assertEquals('email', $prop->getterMethod());
        $this->assertEquals('setEmail', $prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());

        $prop = $metadata->get('address');
        $this->assertFalse($prop->isPublic());
        $this->assertNull($prop->getterMethod());
        $this->assertNull($prop->setterMethod());
        $this->assertInstanceOf(\Closure::class, $prop->privateReader());
        $this->assertInstanceOf(\Closure::class, $prop->privateWriter());
    }

    public function testGetInheritedPropertyMetadataWhenItDoesNotExist()
    {
        $this->expectException(PropertyNotFoundException::class);

        $employee = new Employee(1);
        $metadata = new ObjectMetadata($employee);
        $metadata->get('recordId');
    }
}
