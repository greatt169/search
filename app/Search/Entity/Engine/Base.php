<?php

namespace App\Search\Entity\Engine;

use App\Search\Entity\Interfaces\EntityInterface;

abstract class Base implements EntityInterface
{
    protected $index;

    public function __construct(string $index = null)
    {
        if($index !== null) {
            $this->index = $index;
        }
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index): void
    {
        $this->index = $index;
    }
}