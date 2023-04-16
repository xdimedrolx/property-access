<?php

namespace Bibop\PropertyAccessor\Tests\Metadata;

use Bibop\PropertyAccessor\Metadata\MetadataGenerator;
use Bibop\PropertyAccessor\Tests\Fixtures\Employee;
use PHPUnit\Framework\TestCase;

class MetadataGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $dto = new Employee(1, 'Juan','juan@ya.ru');

        $sut = new MetadataGenerator();

        $actual = $sut->generate($dto);

        $this->assertArrayHasKey('properties', $actual);
        $this->assertArrayHasKey('methods', $actual);

        $properties = $actual['properties'];
        $this->assertArrayHasKey('id', $properties);
        $this->assertEquals('getId', $properties['id']->getterMethod());
        $this->assertEquals('setId', $properties['id']->setterMethod());

        $this->assertArrayHasKey('email', $properties);
        $this->assertEquals('email', $properties['email']->getterMethod());
        $this->assertEquals('setEmail', $properties['email']->setterMethod());

        $methods = $actual['methods'];
        $this->assertArrayHasKey('type', $methods);
        $this->assertEquals('type', $methods['type']->getterMethod());
        $this->assertEquals(null, $methods['type']->setterMethod());
    }
}