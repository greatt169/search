<?php

namespace App\Search\Index\Manager;

class Elasticsearch extends Base
{
    public function createIndex($index)
    {
        // TODO: Implement createIndex() method.
    }

    function dropIndex($index)
    {
        // TODO: Implement dropIndex() method.
    }

    public function indexAll($index)
    {
        // TODO: Implement indexAll() method.
    }

    public function removeAll($index)
    {
        // TODO: Implement removeAll() method.
    }

    public function indexElements($filter = null)
    {
        // TODO: Implement indexElements() method.
    }

    public function indexElement($id)
    {
        // TODO: Implement indexElement() method.
    }

    public function prepareElementsForIndexing()
    {
        dd($this->documents);
        foreach ($this->documents as $document) {

        }
    }
}