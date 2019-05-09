<?php

namespace App\Search\Index\Interfaces;

interface SourceInterface
{
    public function getElementsForIndexing();
    public function getAttributesMapping();
    public function getIndexName();
}