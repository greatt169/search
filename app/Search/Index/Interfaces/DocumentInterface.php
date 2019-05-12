<?php

namespace App\Search\Index\Interfaces;

/**
 * Interface DocumentInterface
 * @package App\Search\Interfaces\Index
 *
 * @property string $id
 * @property DocumentAttributeInterface[] $attributes
 */
interface DocumentInterface
{
    public function getId();

    public function getAttributes();
}