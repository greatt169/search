<?php

namespace App\Search\Entity\Index;

class Document
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