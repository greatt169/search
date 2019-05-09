<?php

namespace App\Search\Index\Interfaces;
/**
 * Interface SourceInterface
 * @package App\Search\Index\Interfaces
 *
 * @method getTypeName
 */
interface SourceInterface
{
    public function getElementsForIndexing();
    public function getAttributesMapping();
    public function getIndexName();
}