<?php

namespace Bibop\PropertyAccessor\Tests\Fixtures;

class Employee extends User
{
    public function type(): string
    {
        return 'employee';
    }
}