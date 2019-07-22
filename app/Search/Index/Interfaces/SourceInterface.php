<?php

namespace App\Search\Index\Interfaces;
/**
 * Interface SourceInterface
 * @package App\Search\Index\Interfaces
 *
 */
interface SourceInterface
{
    public function getElementsForIndexing($rawItems);
    public function getMappingForIndexing();
    public function getIndexName();
    public function getIndexSettings();
}