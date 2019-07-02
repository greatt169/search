<?php

namespace App\Search\Index\Interfaces;
/**
 * Interface SourceInterface
 * @package App\Search\Index\Interfaces
 *
 */
interface SourceInterface
{
    public function getElementsForIndexing();
    public function getIndexName();
}