<?php

namespace Bibop\PropertyAccessor\Tests;

use Bibop\PropertyAccessor\ObjectMetadata;
use Bibop\PropertyAccessor\PropertyAccessor;
use Bibop\PropertyAccessor\Tests\Models\Employee;
use Bibop\PropertyAccessor\Tests\Models\User;
use PHPUnit\Framework\TestCase;

final class PropertyAccessorTest extends TestCase
{
    private $accessor;
    private $user1;
    private $user2;
    private $employee;

    public function setUp(): void
    {
        $this->accessor = new PropertyAccessor();
        $this->user1 = new User(1, 'bibop', 'mail@test.ru', 'msk');
        $this->user2 = new User(2);
        $this->employee = new Employee(1, 'bibop', 'mail@test.ru', 'msk');
    }

    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(PropertyAccessor::class, $this->accessor);
    }

    public function testGetProperty(): void
    {
        $this->assertEquals(1, $this->accessor->getProperty($this->user1, 'id'));
        $this->assertEquals('bibop', $this->accessor->getProperty($this->user1, 'name'));
        $this->assertEquals('mail@test.ru', $this->accessor->getProperty($this->user1, 'email'));
        $this->assertEquals('msk', $this->accessor->getProperty($this->user1, 'address'));

        $this->assertEquals(2, $this->accessor->getProperty($this->user2, 'id'));
        $this->assertNull($this->accessor->getProperty($this->user2, 'name'));
        $this->assertNull($this->accessor->getProperty($this->user2, 'email'));
        $this->assertNull($this->accessor->getProperty($this->user2, 'address'));

        $this->assertEquals(1, $this->accessor->getProperty($this->employee, 'id'));
        $this->assertEquals('bibop', $this->accessor->getProperty($this->employee, 'name'));
        $this->assertEquals('mail@test.ru', $this->accessor->getProperty($this->employee, 'email'));
        $this->assertEquals('msk', $this->accessor->getProperty($this->employee, 'address'));
    }

    public function testSetProperty(): void
    {
        $this->accessor->setProperty($this->user1, 'id', 3);
        $this->accessor->setProperty($this->user1, 'name', 'qwerty');
        $this->accessor->setProperty($this->user1, 'email', 'test@test.ru');
        $this->accessor->setProperty($this->user1, 'address', null);

        $this->accessor->setProperty($this->user2, 'id', 4);
        $this->accessor->setProperty($this->user2, 'name', 'test');
        $this->accessor->setProperty($this->user2, 'email', null);
        $this->accessor->setProperty($this->user2, 'address', 'srk');

        $this->accessor->setProperty($this->employee, 'id', 3);
        $this->accessor->setProperty($this->employee, 'name', 'qwerty');
        $this->accessor->setProperty($this->employee, 'email', 'test@test.ru');
        $this->accessor->setProperty($this->employee, 'address', null);

        $this->assertEquals(3, $this->user1->getId());
        $this->assertEquals('qwerty', $this->user1->name);
        $this->assertEquals('test@test.ru', $this->user1->email());
        $this->assertNull($this->accessor->getProperty($this->user1, 'address'));

        $this->assertEquals(4, $this->user2->getId());
        $this->assertEquals('test', $this->user2->name);
        $this->assertNull($this->user2->email());
        $this->assertEquals('srk', $this->accessor->getProperty($this->user2, 'address'));

        $this->assertEquals(3, $this->employee->getId());
        $this->assertEquals('qwerty', $this->employee->name);
        $this->assertEquals('test@test.ru', $this->employee->email());
        $this->assertNull($this->accessor->getProperty($this->employee, 'address'));
    }

    public function testHasProperty()
    {
        $this->assertTrue($this->accessor->hasProperty($this->user1, 'id'));
        $this->assertTrue($this->accessor->hasProperty($this->user1, 'name'));
        $this->assertTrue($this->accessor->hasProperty($this->user1, 'email'));
        $this->assertTrue($this->accessor->hasProperty($this->user1, 'address'));
        $this->assertFalse($this->accessor->hasProperty($this->user1, 'address1'));

        $this->assertTrue($this->accessor->hasProperty($this->employee, 'id'));
        $this->assertTrue($this->accessor->hasProperty($this->employee, 'name'));
        $this->assertTrue($this->accessor->hasProperty($this->employee, 'email'));
        $this->assertTrue($this->accessor->hasProperty($this->employee, 'address'));
        $this->assertFalse($this->accessor->hasProperty($this->employee, 'address1'));
    }

    public function testGetPropertyNames()
    {
        $this->assertEqualsCanonicalizing(['id', 'name', 'email', 'address'], $this->accessor->getPropertyNames($this->user1));
        $this->assertEqualsCanonicalizing(['id', 'name', 'email', 'address'], $this->accessor->getPropertyNames($this->employee));
    }
}
