<?php

namespace App\Search\Query\Entity;

abstract class Base
{
    protected $index;

    public function __construct(string $index)
    {
        $this->index = $index;
    }
}