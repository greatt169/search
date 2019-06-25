<?php

namespace App\Search\Entity;

abstract class Base
{
    protected $index;

    public function __construct(string $index)
    {
        $this->index = $index;
    }
}