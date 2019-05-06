<?php

namespace App\Search\Index\Interfaces;

interface ManagerInterface
{
    public function createIndex($index);

    public function dropIndex($index);

    public function indexAll($index);

    public function removeAll($index);

    public function indexElements($filter = null);

    public function indexElement($id);

    public function prepareElementsForIndexing($filter = null);
}