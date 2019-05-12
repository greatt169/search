<?php

namespace App\Search\Index\Interfaces;

/**
 * Interface DocumentAttributeInterface
 * @package App\Search\Interfaces\Index
 *
 * @property bool $inQuery
 * @property bool $inBody
 * @property string $code
 * @property string $type
 * @property bool $multiple
 * @property array | string | integer | float $value
 */
interface DocumentAttributeInterface
{
    public function getCode();
    public function getValue();
}