<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Interfaces\DocumentInterface;
use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    protected $source;

    /**
     * @var DocumentInterface[] $documents
     */
    protected $documents;

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