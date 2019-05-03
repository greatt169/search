<?php

namespace App\Search\Entity\Index;

class DocumentAttribute
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $multiple;

    /**
     * @var array | string | integer | float
     */
    public $value;
}