<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    protected $source;

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }
}