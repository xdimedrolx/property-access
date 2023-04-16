<?php

namespace Bibop\PropertyAccessor\Tests\Fixtures;

class AddressWithoutFunctions
{
    public $city;
    protected $street;
    private $build;

    public function __construct($city, $street, $build)
    {
        $this->city = $city;
        $this->street = $street;
        $this->build = $build;
    }
}