<?php

namespace App\Search\Entity\Index;

use App\Search\Interfaces\Index\DocumentInterface;

class Document implements  DocumentInterface
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var DocumentAttribute[]
     */
    public $attributes;
}