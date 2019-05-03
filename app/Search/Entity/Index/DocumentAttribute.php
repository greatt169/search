<?php

namespace App\Search\Entity\Index;

class DocumentAttribute
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
}