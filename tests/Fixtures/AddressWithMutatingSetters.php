<?php

namespace Bibop\PropertyAccessor\Tests\Fixtures;

class AddressWithMutatingSetters
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

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getBuild()
    {
        return $this->build;
    }

    public function setCity($city)
    {
        $this->city = $city . '.';
    }

    public function setStreet($street)
    {
        $this->street = $street . '.';
    }

    public function setBuild($build)
    {
        $this->build = $build . '.';
    }
}