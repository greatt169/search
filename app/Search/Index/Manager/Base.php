<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Entity\DocumentAttribute;
use App\Search\Index\Interfaces\DocumentInterface;
use App\Search\Index\Interfaces\ManagerInterface;

class Base implements ManagerInterface
{
    /**
     * @var DocumentInterface $document
     */
    private $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @param array $source
     * @return DocumentInterface
     */
    public function buildIndexObject(array $source)
    {

        $this->document->id = $source['id'];
        $attributes = [];
        foreach ($source['attributes'] as $attribute) {
            $documentAttribute = new DocumentAttribute($attribute);
            $attributes[] = $documentAttribute;
        }
        $this->document->attributes = $attributes;
        return $this->document;
    }

    public function createIndex($index)
    {
        // TODO: Implement createIndex() method.
    }

    public function dropIndex($index)
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

    public function prepareElementsForIndexing($filter = null)
    {
        // TODO: Implement prepareElementsForIndexing() method.
    }
}