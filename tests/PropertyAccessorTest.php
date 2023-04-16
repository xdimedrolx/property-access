<?php

namespace Bibop\PropertyAccessor\Tests;

use Bibop\PropertyAccessor\PropertyAccessor;
use Bibop\PropertyAccessor\Tests\Fixtures\AddressWithGetters;
use Bibop\PropertyAccessor\Tests\Fixtures\AddressWithMutatingGetters;
use Bibop\PropertyAccessor\Tests\Fixtures\AddressWithMutatingSetters;
use Bibop\PropertyAccessor\Tests\Fixtures\AddressWithoutFunctions;
use Bibop\PropertyAccessor\Tests\Fixtures\Employee;
use Bibop\PropertyAccessor\Tests\Fixtures\User;
use PHPUnit\Framework\TestCase;

final class PropertyAccessorTest extends TestCase
{
    private $user1;
    private $user2;
    private $employee;

    public function setUp(): void
    {
        $this->user1 = new User(1, 'bibop', 'mail@test.ru', 'msk');
        $this->user2 = new User(2);
        $this->employee = new Employee(1, 'bibop', 'mail@test.ru', 'msk');
    }

    public function testGetPublicProperty(): void
    {
        $dto = new AddressWithoutFunctions('moscow', 'ciudad de la paz', '1345');
        $sut = PropertyAccessor::build();
        $this->assertEquals('moscow', $sut->getProperty($dto, 'city'));
    }

    public function testGetProtectedProperty(): void
    {
        $dto = new AddressWithoutFunctions('moscow', 'ciudad de la paz', '1345');
        $sut = PropertyAccessor::build();
        $this->assertEquals('ciudad de la paz', $sut->getProperty($dto, 'street'));
    }

    public function testGetPrivateProperty(): void
    {
        $dto = new AddressWithoutFunctions('moscow', 'ciudad de la paz', '1345');
        $sut = PropertyAccessor::build();
        $this->assertEquals('1345', $sut->getProperty($dto, 'build'));
    }

    public function testSetPublicProperty(): void
    {
        $dto = new AddressWithGetters('moscow', 'ciudad de la paz', '1345');

        $sut = PropertyAccessor::build();
        $sut->setProperty($dto, 'city', 'buenos aires');

        $this->assertEquals('buenos aires', $dto->getCity());
    }

    public function testSetProtectedProperty(): void
    {
        $dto = new AddressWithGetters('moscow', 'ciudad de la paz', '1345');

        $sut = PropertyAccessor::build();
        $sut->setProperty($dto, 'street', 'la plata');

        $this->assertEquals('la plata', $dto->getStreet());
    }

    public function testSetPrivateProperty(): void
    {
        $dto = new AddressWithGetters('moscow', 'ciudad de la paz', '1345');

        $sut = PropertyAccessor::build();
        $sut->setProperty($dto, 'build', '1111');

        $this->assertEquals('1111', $dto->getBuild());
    }

    public function testIfGetterMethodExitsThenItShouldBeUsed(): void
    {
        $dto = new AddressWithMutatingGetters('moscow', 'ciudad de la paz', '1345');

        $sut = PropertyAccessor::build();

        $this->assertEquals('moscow.', $sut->getProperty($dto, 'city'));
        $this->assertEquals('ciudad de la paz.', $sut->getProperty($dto, 'street'));
        $this->assertEquals('1345.', $sut->getProperty($dto, 'build'));
    }

    public function testIfSetterMethodExitsThenItShouldBeUsed(): void
    {
        $dto = new AddressWithMutatingSetters('moscow', 'ciudad de la paz', '1345');

        $sut = PropertyAccessor::build();

        $sut->setProperty($dto, 'city', 'buenos aires');
        $sut->setProperty($dto, 'street', 'la plata');
        $sut->setProperty($dto, 'build', '1111');

        $this->assertEquals('buenos aires.', $dto->getCity());
        $this->assertEquals('la plata.', $dto->getStreet());
        $this->assertEquals('1111.', $dto->getBuild());
    }

    public function testGetterMethodWhenPropertyDoesNotExist(): void
    {
        $dto = new Employee(1, 'Juan');

        $sut = PropertyAccessor::build();

        $type = $sut->getProperty($dto, 'type');

        $this->assertEquals('employee', $type);
    }

//    public function testGetPropertyNames()
//    {
//        $this->assertEqualsCanonicalizing(['id', 'name', 'email', 'address'], $this->accessor->getPropertyNames($this->user1));
//        $this->assertEqualsCanonicalizing(['id', 'name', 'email', 'address'], $this->accessor->getPropertyNames($this->employee));
//    }
}
