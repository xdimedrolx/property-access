<?php

namespace Bibop\PropertyAccessor\Tests\Models;

class User
{
    private $id;
    public $name;
    protected $email;
    private $address;

    public function __construct($id, $name = null, $email = null, $address = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function email()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}