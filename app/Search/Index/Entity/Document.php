<?php

namespace App\Search\Index\Entity;

use App\Search\Index\Interfaces\DocumentAttributeInterface;
use App\Search\Index\Interfaces\DocumentInterface;

class Document implements  DocumentInterface
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var DocumentAttributeInterface[]
     */
    public $attributes;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DocumentAttributeInterface[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}