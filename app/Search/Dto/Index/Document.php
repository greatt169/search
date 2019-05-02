<?php

namespace App\Search\Dto\Index;

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