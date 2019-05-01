<?php

namespace App\Search\Index;

interface IndexInterface
{
    public function createIndex();

    public function dropIndex();

    public function indexAll();

    public function removeAll();

    public function getElements();

    public function getElement();

    public function prepareElementsForIndexing();
}