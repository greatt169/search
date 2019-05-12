<?php

namespace App\Search\Index\Entity;

use App\Search\Index\Interfaces\DocumentAttributeInterface;

class DocumentAttribute implements DocumentAttributeInterface
{
    /**
     * @var bool
     */
    protected $inQuery;

    /**
     * @var bool
     */
    protected $inBody;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $multiple;

    /**
     * @var array | string | integer | float
     */
    protected $value;

    /**
     * DocumentAttribute constructor.
     * @param array $sourceAttribute
     */
    public function __construct(array $sourceAttribute)
    {
        $this->code = $sourceAttribute['code'];
        $this->type = $sourceAttribute['type'];
        $this->multiple = $sourceAttribute['multiple'];
        $this->value = $sourceAttribute['value'];
        $this->inQuery = $sourceAttribute['in_query'];
        $this->inBody = $sourceAttribute['in_body'];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array|float|int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}